<?php

declare(strict_types = 1);

namespace App\Presentation\UI\_config;

trait PaymentViewConfigTrait
{
    protected function getViewConfig(): array
    {
        return [
            'searchFields' => ['amount', 'createdAt', 'updatedAt', 'status'],
            'headers' => [
                'id' => [
                    'translation' => 'id',
                    'key' => 'id',
                    'functions' => [],
                    'filters' => [],
                ],
                'createdAt' => [
                    'translation' => 'createdAt',
                    'key' => 'createdAt',
                    'functions' => [],
                    'filters' => ['paymentDate'],
                ],
                'amount' => [
                    'translation' => 'amount',
                    'key' => 'amount',
                    'functions' => [],
                    'filters' => ['money'],
                ],
                'commission' => [
                    'translation' => 'commission',
                    'key' => 'commission',
                    'functions' => [],
                    'filters' => ['money'],
                ],
                'status' => [
                    'translation' => 'status',
                    'key' => 'status',
                    'functions' => ['paymentStatus'],
                    'filters' => ['raw'],
                ],
                'gateway' => [
                    'translation' => 'gateway',
                    'key' => 'gateway.title',
                    'functions' => [],
                    'filters' => [],
                ],
                'store' => [
                    'translation' => 'store',
                    'key' => 'store.title',
                    'functions' => [],
                    'filters' => [],
                ],
                'currency' => [
                    'translation' => 'currency',
                    'key' => 'currency',
                    'functions' => [],
                    'filters' => [],
                ],
                'qr' => [
                    'translation' => 'qr.title',
                    'key' => 'qr',
                    'functions' => ['qr'],
                    'filters' => [],
                ],
            ],
        ];
    }
}
