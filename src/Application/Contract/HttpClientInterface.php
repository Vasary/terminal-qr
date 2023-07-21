<?php

declare(strict_types = 1);

namespace App\Application\Contract;

use App\Domain\ValueObject\Portal;
use App\Domain\ValueObject\Token;
use App\Infrastructure\HTTP\Response\CheckPaymentStatusResponse;
use App\Infrastructure\HTTP\Response\RegisterPaymentResponse;

interface HttpClientInterface
{
    public function getToken(Portal $portal): string;

    public function registerPayment(Portal $portal, Token $token): RegisterPaymentResponse;

    public function checkStatus(Portal $portal, Token $token): CheckPaymentStatusResponse;
}
