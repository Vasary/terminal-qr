<?php

declare(strict_types = 1);

namespace App\Infrastructure\Test;

use App\Infrastructure\Test\Context\ModelContextInterface;

trait CleanModelContextTrait
{
    protected function cleanModelsContexts(): void
    {
        $models = array_map(
            fn (string $file) => substr($file, 0, -4),
            preg_grep(
                '~\.(php)$~',
                scandir(__DIR__ . DIRECTORY_SEPARATOR . 'Context' . DIRECTORY_SEPARATOR . 'Model')
            )
        );

        foreach ($models as $model) {
            /** @var ModelContextInterface $context */
            $context = 'App\\Infrastructure\\Test\\Context\\Model\\' . $model;
            $context::clean();
        }
    }
}
