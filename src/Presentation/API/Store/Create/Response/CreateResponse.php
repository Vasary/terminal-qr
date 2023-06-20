<?php

declare(strict_types = 1);

namespace App\Presentation\API\Store\Create\Response;

use App\Domain\Model\Store;
use App\Infrastructure\HTTP\JsonResponse;
use App\Infrastructure\Serializer\Serializer;

final class CreateResponse extends JsonResponse
{
    public function __construct(Store $attribute)
    {
        parent::__construct($this->build($attribute), 201);
    }

    private function build(Store $attribute): array
    {
        return Serializer::create()->toArray($attribute);
    }
}
