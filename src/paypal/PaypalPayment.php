<?php

namespace ProjetDesignPattern\paypal;


use ProjetDesignPattern\core\Currency;
use ProjetDesignPattern\core\Payment;
use ProjetDesignPattern\core\PaymentMethod;
use Sample\PayPalClient;

class PayPalPayment extends Payment
{
    private $paypalClient;

    public function initialize(array $credentials, array $config = []): void
    {
        $this->paypalClient = new PayPalClient();
    }

    public function createTransaction(float $amount, Currency $currency, string $description): string
    {
        throw new \Exception('Not implemented yet.');
    }

    public function executeTransaction(string $transactionId, PaymentMethod $paymentMethod, array $paymentInfo): bool
    {
        throw new \Exception('Not implemented yet.');
    }

    public function cancelTransaction(string $transactionId): bool
    {
        throw new \Exception('Not implemented yet.');
    }

    public function getTransactionStatus(string $transactionId): string
    {
        throw new \Exception('Not implemented yet.');
    }
}
