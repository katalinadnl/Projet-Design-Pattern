<?php

namespace ProjetDesignPattern\Factory;

use ProjetDesignPattern\Interfaces\PaymentInterface;
use ProjetDesignPattern\PaymentGateways\PaypalPayment;
use ProjetDesignPattern\PaymentGateways\StripePayment;

class PaymentFactory {
    public static function createPaymentGateway(string $type): PaymentInterface {
        switch ($type) {
            case 'stripe':
                return new StripePayment();
            case 'paypal':
                return new PayPalPayment();
            default:
                throw new Exception("Unsupported payment gateway");
        }
    }
}
