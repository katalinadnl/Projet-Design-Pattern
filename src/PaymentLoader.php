<?php

namespace ProjetDesignPattern;

use ProjetDesignPattern\core\Payment;
use ProjetDesignPattern\core\PaymentProviderName;

require 'stripe/StripePayment.php';

class PaymentLoader //Payment library API // Facade
{

    public static function getPayment(PaymentProviderName $paymentProviderName, array $credentials): Payment
    {
        $payment = PaymentFactory::getPayment($paymentProviderName);
        $payment->initialize($credentials);

        return $payment;
    }

}