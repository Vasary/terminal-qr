<?php

declare(strict_types = 1);

namespace App\Infrastructure\HTTP;

use App\Infrastructure\HTTP\Exception\ValidationException;
use ReflectionClass;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\RequestStack as SymfonyRequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRequest
{
    protected const EXCLUDE_FIELDS = [
        'validator',
        'request',
        'translator',
    ];

    public function __construct(protected ValidatorInterface $validator, private readonly SymfonyRequestStack $request) {
        $this->populate();

        if ($this->autoValidateRequest()) {
            $this->validate();
        }
    }

    public function validate(): void
    {
        $errors = $this->validator->validate($this);

        if ($errors->count() > 0) {
            throw new ValidationException($errors);
        }
    }

    public function getRequest(): SymfonyRequest
    {
        return $this->request->getCurrentRequest();
    }

    public function toArray(): array
    {
        $reflection = new ReflectionClass($this);

        $properties = [];
        foreach ($reflection->getProperties() as $property) {
            if (!in_array($property->name, static::EXCLUDE_FIELDS)) {
                $properties[$property->name] = $property->getValue($this);
            }
        }

        return $properties;
    }

    protected function populate(): void
    {
        $data = $this->retrieveData();
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties() as $property) {
            if (in_array($property->getName(), self::EXCLUDE_FIELDS)) {
                continue;
            }

            if (isset($data[$property->getName()])) {
                $this->{$property->getName()} = $data[$property->getName()];
            } else {
                if (!$property->isInitialized($this)) {
                    if ($property->getType()->allowsNull()) {
                        $this->{$property->getName()} = null;
                    } else {
                        throw new RuntimeException('Property is not initialized and has no value');
                    }
                }
            }
        }
    }

    protected function retrieveData(): array
    {
        return $this->getRequest()->toArray();
    }

    protected function autoValidateRequest(): bool
    {
        return true;
    }
}
