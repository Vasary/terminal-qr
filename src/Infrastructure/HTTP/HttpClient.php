<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP;

use App\Application\Contract\HttpClientInterface;
use App\Domain\ValueObject\Portal;
use App\Domain\ValueObject\Token;
use App\Infrastructure\HTTP\Exception\TokenizationException;
use App\Infrastructure\HTTP\Exception\TransactionRegistrationException;
use App\Infrastructure\HTTP\Response\CheckPaymentStatusResponse;
use App\Infrastructure\HTTP\Response\RegisterPaymentResponse;
use App\Infrastructure\HTTP\Response\TokenResponse;
use App\Infrastructure\Serializer\SerializerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

final readonly class HttpClient implements HttpClientInterface
{
    public function __construct(private Client $client, private SerializerInterface $serializer)
    {
    }

    public function getToken(Portal $portal): string
    {
        $url = sprintf('/api/v4/%s/token', $portal);

        $request = $this->client->postAsync($url)
            ->then(
                fn(ResponseInterface $response) => $this->createTokenResponse($response),
                fn(RequestException $exception) => throw new TokenizationException(
                    message: 'Payment tokenization exception',
                    previous: $exception
                ),
            );

        return $request->wait();
    }

    public function registerPayment(Portal $portal, Token $token): RegisterPaymentResponse
    {
        $url = sprintf('/api/v4/%s/payment/%s/start', $portal, $token);

        $request = $this->client->postAsync($url)
            ->then(
                fn(ResponseInterface $response) => $this->createRegisterPaymentResponse($response),
                fn(RequestException $exception) => throw new TransactionRegistrationException(
                    message: 'Transaction processing exception',
                    previous: $exception
                ),
            );

        return $request->wait();
    }

    public function checkStatus(Portal $portal, Token $token): CheckPaymentStatusResponse
    {
        return new CheckPaymentStatusResponse();
    }

    private function createTokenResponse(ResponseInterface $response): string
    {
        /** @var TokenResponse $token */
        $token = $this->serializer->deserialize((string) $response->getBody(), TokenResponse::class);

        if (null === $token->token()) {
            throw new TokenizationException('Token is not existing in the response');
        }

        return $token->token();
    }

    private function createRegisterPaymentResponse(ResponseInterface $response): RegisterPaymentResponse
    {
        /** @var RegisterPaymentResponse $registerPaymentResponse */
        $registerPaymentResponse = $this
            ->serializer
            ->deserialize((string) $response->getBody(), RegisterPaymentResponse::class);

        if (null === $registerPaymentResponse->result()) {
            throw new TransactionRegistrationException('Result object is not existing in the response');
        }

        return $registerPaymentResponse;
    }
}
