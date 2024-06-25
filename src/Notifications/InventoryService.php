<?php

namespace ProjetDesignPattern\Notifications;

use ProjetDesignPattern\Interfaces\Observer;

class InventoryService implements Observer
{
    public function update(string $transactionId, string $status)
    {
        // Implement inventory service notification logic here
        echo "InventoryService: Transaction $transactionId has a status of $status\n";
    }
}
