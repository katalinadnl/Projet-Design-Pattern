<?php

namespace ProjetDesignPattern\Interfaces;

interface PaymentInterface {
    public function initialize(array $credentials);
    public function createTransaction(float $amount, string $currency, string $description);
    public function executeTransaction();
    public function cancelTransaction();
    public function getTransactionStatus(): string;
}
