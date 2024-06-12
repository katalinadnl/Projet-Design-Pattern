<?php

namespace PaymentLibrary\Interfaces;

interface TransactionState
{
    public function process(\PaymentLibrary\Transactions\Transaction $transaction): void;
    public function cancel(\PaymentLibrary\Transactions\Transaction $transaction): void;
}
