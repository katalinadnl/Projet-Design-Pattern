<?php

namespace ProjetDesignPattern\Interfaces;

interface PaymentInterface {
    public function initialize($credentials);
    public function createTransaction($amount, $currency, $description);
    public function executeTransaction();
    public function cancelTransaction();
    public function getTransactionStatus();
}
?>
