<?php

declare(strict_types=1);

namespace App\Interfaces;

interface EmailValidationInterface
{
    public function verify(string $email): array;
}
