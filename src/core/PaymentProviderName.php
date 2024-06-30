<?php

namespace ProjetDesignPattern\core;

enum PaymentProviderName: string
{
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';
}
