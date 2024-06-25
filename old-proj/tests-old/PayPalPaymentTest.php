<?php

namespace ProjetDesignPattern\test;

use PHPUnit\Framework\TestCase;
use ProjetDesignPattern\Services\PaypalPayment;

class PayPalPaymentTest extends TestCase {
    public function testInitialize() {
        $paypal = new PaypalPayment();
        $credentials = ['client_id' => 'your_client_id', 'client_secret' => 'your_client_secret'];
        $paypal->initialize($credentials);

        $this->assertNotEmpty($paypal->getTransactionStatus());
    }

    public function testCreateTransaction() {
        $paypal = new PaypalPayment();
        $paypal->initialize(['client_id' => 'your_client_id', 'client_secret' => 'your_client_secret']);
        $paypal->createTransaction(100, 'USD', 'Test Payment');

        $this->assertEquals('pending', $paypal->getTransactionStatus());
    }

    public function testExecuteTransaction() {
        $paypal = new PaypalPayment();
        $paypal->initialize(['client_id' => 'your_client_id', 'client_secret' => 'your_client_secret']);
        $paypal->createTransaction(100, 'USD', 'Test Payment');
        $result = $paypal->executeTransaction();

        $this->assertTrue($result);
        $this->assertEquals('success', $paypal->getTransactionStatus());
    }

    public function testCancelTransaction() {
        $paypal = new PaypalPayment();
        $paypal->initialize(['client_id' => 'your_client_id', 'client_secret' => 'your_client_secret']);
        $paypal->createTransaction(100, 'USD', 'Test Payment');
        $paypal->cancelTransaction();

        $this->assertEquals('cancelled', $paypal->getTransactionStatus());
    }

    public function testGetTransactionStatus() {
        $paypal = new PaypalPayment();
        $paypal->initialize(['client_id' => 'your_client_id', 'client_secret' => 'your_client_secret']);
        $paypal->createTransaction(100, 'USD', 'Test Payment');
        $status = $paypal->getTransactionStatus();

        $this->assertNotEmpty($status);
    }
}
?>
