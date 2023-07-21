<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Terminal\Response;

use App\Domain\Model\Payment;
use App\Infrastructure\HTTP\JsonResponse;

final class PaymentStatusResponse extends JsonResponse
{
    public function __construct(Payment $payment)
    {
        $data = [
            'id' => (string) $payment->id(),
            'status' => $payment->status()->name,
        ];

        parent::__construct($data);
    }
}
