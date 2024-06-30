<?php

namespace ProjetDesignPattern;

use Exception;
use ProjetDesignPattern\core\Payment;
use ProjetDesignPattern\core\PaymentProviderName;
use ProjetDesignPattern\stripe\StripePayment;

class PaymentFactory { // Factory
    public static function getPayment(PaymentProviderName $providerName): Payment {
        switch ($providerName) {
            case PaymentProviderName::STRIPE:
                return StripePayment::getInstance();
            //case PaymentProvider::PAYPAL:
              //  return PaypalPayment::getInstance();
            default:
                throw new Exception("Unsupported payment gateway");
        }
    }
}
