<?php

declare(strict_types = 1);

use App\Presentation\UI\Management\Module\Gateway\Form\Credential;

if (!function_exists('flattenCredentialsArray')) {
    function flattenCredentialsArray(array $array): array
    {
        $return = [];

        $multidimensional = array_map(
            fn (Credential $credential) => $credential->toArray(),
            $array,
        );

        foreach ($multidimensional as $value) {
            $return[$value['key']] = $value['value'];
        }

        return $return;
    }
}
