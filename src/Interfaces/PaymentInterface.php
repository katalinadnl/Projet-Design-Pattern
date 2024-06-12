<?php

namespace PaymentLibrary\Interfaces;

interface PaymentInterface
{
    public function initialize(array $credentials): void;
    public function createTransaction(float $amount, string $currency, string $description): void;
    public function executeTransaction(): bool;
    public function cancelTransaction(): bool;
    public function getStatus(): string;
}
