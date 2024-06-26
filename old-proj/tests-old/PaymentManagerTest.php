<?php

namespace ProjetDesignPattern\test;

use PHPUnit\Framework\TestCase;
use ProjetDesignPattern\Services\PaymentManager;
use ProjetDesignPattern\Services\TransactionState;
use ProjetDesignPattern\Interfaces\PaymentInterface;
use ProjetDesignPattern\Interfaces\NotificationInterface;

class PaymentManagerTest extends TestCase {
    public function testInitialize() {
        $paymentGateway = $this->createMock(PaymentInterface::class);
        $notificationService = $this->createMock(NotificationInterface::class);
        $transactionState = new TransactionState();

        $paymentManager = new PaymentManager($paymentGateway, $notificationService, $transactionState);
        $credentials = ['key' => 'value'];
        $paymentGateway->expects($this->once())
            ->method('initialize')
            ->with($credentials);

        $paymentManager->initialize($credentials);
    }

    public function testCreateTransaction() {
        $paymentGateway = $this->createMock(PaymentInterface::class);
        $notificationService = $this->createMock(NotificationInterface::class);
        $transactionState = new TransactionState();

        $paymentManager = new PaymentManager($paymentGateway, $notificationService, $transactionState);
        $paymentGateway->expects($this->once())
            ->method('createTransaction')
            ->with(100, 'USD', 'Test Payment');

        $paymentManager->createTransaction(100, 'USD', 'Test Payment');
    }

    public function testExecuteTransaction() {
        $paymentGateway = $this->createMock(PaymentInterface::class);
        $notificationService = $this->createMock(NotificationInterface::class);
        $transactionState = new TransactionState();

        $paymentManager = new PaymentManager($paymentGateway, $notificationService, $transactionState);
        $paymentManager->initialize(['api_key' => 'test_key']);
        $paymentManager->createTransaction(100, 'USD', 'Test Payment');

        $paymentGateway->expects($this->once())
            ->method('executeTransaction')
            ->willReturn(true);

        $paymentManager->executeTransaction();

        $this->assertEquals(TransactionState::SUCCESS, $paymentManager->getTransactionStatus());
    }

    public function testCancelTransaction() {
        $paymentGateway = $this->createMock(PaymentInterface::class);
        $notificationService = $this->createMock(NotificationInterface::class);
        $transactionState = new TransactionState();

        $paymentManager = new PaymentManager($paymentGateway, $notificationService, $transactionState);
        $paymentManager->initialize(['api_key' => 'test_key']);
        $paymentManager->createTransaction(100, 'USD', 'Test Payment');

        $paymentGateway->expects($this->once())
            ->method('cancelTransaction');

        $notificationService->expects($this->once())
            ->method('notify')
            ->with('Transaction cancelled');

        $paymentManager->cancelTransaction();
        $this->assertEquals(TransactionState::CANCELLED, $paymentManager->getTransactionStatus());
    }

    public function testGetTransactionStatus() {
        $paymentGateway = $this->createMock(PaymentInterface::class);
        $notificationService = $this->createMock(NotificationInterface::class);
        $transactionState = new TransactionState();

        $paymentManager = new PaymentManager($paymentGateway, $notificationService, $transactionState);
        $paymentManager->initialize(['api_key' => 'test_key']);
        $paymentManager->createTransaction(100, 'USD', 'Test Payment');

        $paymentGateway->expects($this->once())
            ->method('executeTransaction')
            ->willReturn(true);

        $paymentManager->executeTransaction();
        $this->assertEquals(TransactionState::SUCCESS, $paymentManager->getTransactionStatus());
    }
}
?>
