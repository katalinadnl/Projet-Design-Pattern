<?php

namespace ProjetDesignPattern\Transactions;

use ProjetDesignPattern\Interfaces\PaymentInterface;
use ProjetDesignPattern\Factory\PaymentFactory;
use ProjetDesignPattern\Notifications\PaymentNotifier;
use ProjetDesignPattern\Interfaces\Observer;

class PaymentTransaction
{
    private $paymentGateway;
    private $notifier;

    public function __construct(string $type)
    {
        $this->paymentGateway = PaymentFactory::createPaymentGateway($type);
        $this->notifier = new PaymentNotifier();
    }

    public function initialize(array $credentials)
    {
        $this->paymentGateway->initialize($credentials);
    }

    public function createTransaction(float $amount, string $currency, string $description)
    {
        $this->paymentGateway->createTransaction($amount, $currency, $description);
    }

    public function executeTransaction()
    {
        $this->paymentGateway->executeTransaction();
        $this->notifyObservers($this->paymentGateway->getTransactionStatus());
    }

    public function cancelTransaction()
    {
        $this->paymentGateway->cancelTransaction();
        $this->notifyObservers($this->paymentGateway->getTransactionStatus());
    }

    public function getTransactionStatus(): string
    {
        return $this->paymentGateway->getTransactionStatus();
    }

    public function addObserver(Observer $observer)
    {
        $this->notifier->addObserver($observer);
    }

    public function removeObserver(Observer $observer)
    {
        $this->notifier->removeObserver($observer);
    }

    private function notifyObservers(string $status)
    {
        $this->notifier->notifyObservers($this->paymentGateway->getTransactionStatus(), $status);
    }
}
