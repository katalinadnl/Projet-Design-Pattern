<?php

namespace ProjetDesignPattern\Services;

use ProjetDesignPattern\Interfaces\PaymentInterface;
use ProjetDesignPattern\Interfaces\NotificationInterface;

class PaymentManager {
    private $paymentGateway;
    private $transactionState;
    private $notificationService;

    public function __construct(PaymentInterface $paymentGateway, NotificationInterface $notificationService, TransactionState $transactionState) {
        $this->paymentGateway = $paymentGateway;
        $this->notificationService = $notificationService;
        $this->transactionState = $transactionState;
    }

    public function initialize($credentials) {
        $this->paymentGateway->initialize($credentials);
    }

    public function createTransaction($amount, $currency, $description) {
        $this->paymentGateway->createTransaction($amount, $currency, $description);
        $this->transactionState->setState(TransactionState::PENDING);
    }

    public function executeTransaction() {
        $result = $this->paymentGateway->executeTransaction();
        if ($result) {
            $this->transactionState->setState(TransactionState::SUCCESS);
        } else {
            $this->transactionState->setState(TransactionState::FAILED);
        }
        $this->notificationService->notify('Transaction ' . $this->transactionState->getState());
        return $result;
    }

    public function cancelTransaction() {
        $this->paymentGateway->cancelTransaction();
        $this->transactionState->setState(TransactionState::CANCELLED);
        $this->notificationService->notify('Transaction cancelled');
    }

    public function getTransactionStatus(): string
    {
        return $this->transactionState->getState();
    }
}
?>
