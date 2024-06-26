<?php

namespace ProjetDesignPattern\PaymentGateways;

use ProjetDesignPattern\Interfaces\PaymentInterface;

class StripePayment implements PaymentInterface
{

    private $apiKey;
    private $transactionId;

    public function initialize(array $credentials)
    {
        if (isset($credentials['api_key'])) {
            $this->apiKey = $credentials['api_key'];
        } else {
            throw new \InvalidArgumentException('API key is required.');
        }

    }

    public function createTransaction(float $amount, string $currency, string $description)
    {
        try {
            $this->paymentIntent = PaymentIntent::create([ // Stripe API call
                'amount' => $amount * 100, // Stripe expects the amount in cents
                'currency' => $currency,
                'description' => $description,
                'payment_method_types' => ['card'],
            ]);
            $this->transactionId = $this->paymentIntent->id;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create transaction: ' . $e->getMessage());
        }
    }

    public function executeTransaction()
    {
        if (!$this->transactionId) { // Check if a transaction has been created
            throw new \Exception('No transaction created.');
        }

        try { // Stripe API call
            $this->paymentIntent = PaymentIntent::retrieve($this->transactionId);  // Retrieve the transaction by ID and confirm it
            $this->paymentIntent->confirm();
        } catch (\Exception $e) {
            throw new \Exception('Failed to execute transaction: ' . $e->getMessage());
        }
    }

    public function cancelTransaction()
    {
        if (!$this->transactionId) {
            throw new \Exception('No transaction created.');
        }

        try {
            $this->paymentIntent = PaymentIntent::retrieve($this->transactionId);
            $this->paymentIntent->cancel();
        } catch (\Exception $e) {
            throw new \Exception('Failed to cancel transaction: ' . $e->getMessage());
        }
    }

    public function getTransactionStatus(): string
    {
        if (!$this->transactionId) {
            throw new \Exception('No transaction created.');
        }

        try {
            $this->paymentIntent = PaymentIntent::retrieve($this->transactionId);
            return $this->paymentIntent->status;
        } catch (\Exception $e) {
            throw new \Exception('Failed to retrieve transaction status: ' . $e->getMessage());
        }
    }
}
