<?php

namespace ProjetDesignPattern\Services;

use ProjetDesignPattern\Interfaces\PaymentInterface;

class PaypalPayment implements PaymentInterface {
    private $apiCredentials;
    private $status;

    //intialise le paiement
    public function initialize($credentials) {
        $this->apiCredentials = $credentials;
        $this->status = 'initialized';
    }
    //crée une transaction
    public function createTransaction($amount, $currency, $description) {
        $this->status = 'pending';
    }
    //execute la transaction
    public function executeTransaction(): bool
    {
        $this->status = 'success';
        return true;
    }
    //annule la transaction
    public function cancelTransaction() {
        $this->status = 'cancelled';
    }
    //retourne le status de la transaction
    public function getTransactionStatus() {
        return $this->status;
    }
}
?>
