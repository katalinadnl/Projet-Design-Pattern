<?php

namespace ProjetDesignPattern\stripe;

use ProjetDesignPattern\core\PaymentMethodStrategy;


class StripeBankTransferPaymentMethodStrategy implements PaymentMethodStrategy
{
    public function __construct()
    {
    }

    public function execute(): bool
    {
        throw new \Exception('Bank transfer payment method is not implemented yet.');
    }
}