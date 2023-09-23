<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Form;

use App\Domain\ValueObject\Credentials\SPB;
use App\Domain\ValueObject\Credentials\Stub;
use App\Infrastructure\Assert\All;
use App\Infrastructure\Assert\Collection;
use App\Infrastructure\Assert\Length;
use App\Infrastructure\Assert\NotBlank;
use App\Infrastructure\Assert\Type;
use App\Infrastructure\Assert\Url;
use App\Infrastructure\Assert\Valid;
use App\Infrastructure\Form\Data\PropertyAccessorTrait;
use LogicException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class Data
{
    use PropertyAccessorTrait;

    #[NotBlank]
    #[Url]
    private string $callback;

    #[Length(min: 1, max: 255)]
    #[NotBlank]
    private string $title;

    #[NotBlank]
    private string $type;

    #[NotBlank]
    private string $currency;

    /**
     * @var Credential[] $credentials
     */
    #[All([new Type(Credential::class)])]
    #[Valid]
    private array $credentials;

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        $credentials = flattenCredentialsArray($this->credentials);

        $constraintsCollection = [
            SPB::class => [],
            Stub::class => [
                new Collection([
                    'login' => [
                        new NotBlank(),
                        new Length(min: 1, max: 50),
                    ],
                    'password' => [
                        new NotBlank(),
                        new Length(min: 1, max: 50),
                    ],
                ]),
            ],
        ];

        $extractViolationsIndex = function (?string $name) use ($credentials) {
            $name = substr($name, 1, -1);
            $i = 0;
            foreach (array_keys($credentials) as $key) {
                if ($name === $key) {
                    return $i;
                }

                $i++;
            }

            throw new LogicException('Invalid property path');
        };

        $constraints = $constraintsCollection[$this->type];
        $violations = $context->getValidator()->validate($credentials, $constraints);
        if ($violations->count() > 0) {
            foreach ($violations as $violation) {
                $index = $extractViolationsIndex($violation->getPropertyPath());

                $context
                    ->buildViolation($violation->getMessage())
                    ->setTranslationDomain('messages')
                    ->atPath(sprintf('credentials[%s].key', $index))
                    ->addViolation();
            }
        }
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
            'callback' => $this->callback,
            'currency' => $this->currency,
            'credentials' => array_map(
                fn(Credential $credential) => $credential->toArray(),
                $this->credentials,
            ),
        ];
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function withCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function callback(): string
    {
        return $this->callback;
    }

    public function withCallback(string $callback): self
    {
        $this->callback = $callback;
        return $this;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function withTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function withType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function credentials(): array
    {
        return $this->credentials;
    }

    public function withCredential(Credential $credential): self
    {
        $this->credentials[] = $credential;
        return $this;
    }

    public function withCredentials(array $credentials): self
    {
        $this->credentials = $credentials;
        return $this;
    }
}
