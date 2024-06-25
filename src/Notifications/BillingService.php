<?php

namespace ProjetDesignPattern\Notifications;

use ProjetDesignPattern\Interfaces\Observer;

class BillingService implements Observer
{
    public function update(string $transactionId, string $status)
    {
        // Implement billing service notification logic here
        echo "BillingService: Transaction $transactionId has a status of $status\n";
    }
}
