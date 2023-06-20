<?php

declare(strict_types = 1);

namespace App\Shared\Transfer;

trait CreateFromTrait
{
    final public static function fromArray(array $data = []): self
    {
        $reflection = new \ReflectionClass(self::class);
        /** @var self $instance * */
        $instance = (new \ReflectionClass(self::class))->newInstanceWithoutConstructor();

        foreach ($reflection->getProperties() as $property) {
            if (!isset($data[$property->name]) && $property->getType()->allowsNull()) {
                $property->setValue($instance, null);

                continue;
            }

            if ('array' === $property->getType()->getName()) {
                $type = '';
                preg_match('/\/\*\*\s@var\s(.+)\s\*\//m', $property->getDocComment(), $matches);

                if (2 === count($matches)) {
                    $type = $matches[1];
                    if (str_ends_with($type, '|null')) {
                        $type = substr($type, 0, -5);
                    }

                    if (str_ends_with($type, '[]')) {
                        $type = substr($type, 0, -2);
                    }
                }

                if (in_array($type, ['string', 'integer', 'bool', 'float', 'int'])) {
                    self::setScalarDataToArray($data, $property, $instance);
                } else {
                    self::setClassObject($type, $data, $property, $instance);
                }

                continue;
            }

            $property->setValue($instance, $data[$property->name]);
        }

        return $instance;
    }

    final public static function setScalarDataToArray(
        array $data,
        \ReflectionProperty $property,
        object $instance,
    ): void {
        $property->setValue($instance, $data[$property->name]);
    }

    final public static function setClassObject(
        string $class,
        array $data,
        \ReflectionProperty $property,
        object $instance,
    ): void {
        $class = __NAMESPACE__ . '\\' . $class;
        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf('%s class is not exists', $class));
        }

        try {

            $property->setValue(
                $instance,
                array_map(
                    fn (array $propertiesCollection) => $class::fromArray($propertiesCollection),
                    $data[$property->name]
                )
            );
        } catch (\Throwable $throwable) {
            dd($data[$property->name], $throwable->getMessage());
        }

    }
}
