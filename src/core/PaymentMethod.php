<?php

namespace ProjetDesignPattern\core;

enum PaymentMethod: string
{
    case CARD = 'card';
    case BANK_TRANSFER = 'bank_transfer';
}
