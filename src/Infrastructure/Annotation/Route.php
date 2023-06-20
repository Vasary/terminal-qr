<?php

declare(strict_types = 1);

namespace App\Infrastructure\Annotation;

use Attribute;
use Symfony\Component\Routing\Annotation\Route as SymfonyRouteAnnotation;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Route extends SymfonyRouteAnnotation
{
}
