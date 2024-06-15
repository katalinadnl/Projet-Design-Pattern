<?php

namespace ProjetDesignPattern\test;

use PHPUnit\Framework\TestCase;
use ProjetDesignPattern\Services\StripePayment;

class StripePaymentTest extends TestCase {
    public function testInitialize() {
        $stripe = new StripePayment();
        $credentials = ['api_key' => 'your_stripe_api_key'];
        $stripe->initialize($credentials);

        $this->assertNotEmpty($stripe->getTransactionStatus());
    }

    public function testCreateTransaction() {
        $stripe = new StripePayment();
        $stripe->initialize(['api_key' => 'your_stripe_api_key']);
        $stripe->createTransaction(100, 'USD', 'Test Payment');

        $this->assertEquals('pending', $stripe->getTransactionStatus());
    }

    public function testExecuteTransaction() {
        $stripe = new StripePayment();
        $stripe->initialize(['api_key' => 'your_stripe_api_key']);
        $stripe->createTransaction(100, 'USD', 'Test Payment');
        $result = $stripe->executeTransaction();

        $this->assertTrue($result);
        $this->assertEquals('success', $stripe->getTransactionStatus());
    }

    public function testCancelTransaction() {
        $stripe = new StripePayment();
        $stripe->initialize(['api_key' => 'your_stripe_api_key']);
        $stripe->createTransaction(100, 'USD', 'Test Payment');
        $stripe->cancelTransaction();

        $this->assertEquals('cancelled', $stripe->getTransactionStatus());
    }

    public function testGetTransactionStatus() {
        $stripe = new StripePayment();
        $stripe->initialize(['api_key' => 'your_stripe_api_key']);
        $stripe->createTransaction(100, 'USD', 'Test Payment');
        $status = $stripe->getTransactionStatus();

        $this->assertNotEmpty($status);
    }
}
?>
