<?php

declare(strict_types = 1);

namespace App\Presentation\UI\Management\Module\Gateway\Controller\Trait;

use App\Presentation\UI\Management\Module\Gateway\Form\Data;

trait CredentialsExtractorTrait
{
    private function extractCredentials(Data $data): array
    {
        $credentials = ['type' => $data->type()];
        foreach ($data->credentials() as $credential) {
            $credentials[$credential->key()] = $credential->value();
        }

        return $credentials;
    }
}
