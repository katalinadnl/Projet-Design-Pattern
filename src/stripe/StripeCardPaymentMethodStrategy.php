<?php

namespace ProjetDesignPattern\stripe;

use ProjetDesignPattern\core\PaymentMethodStrategy;
use Stripe\StripeClient;


class StripeCardPaymentMethodStrategy implements PaymentMethodStrategy
{
    private StripeClient $stripeClient;
    private string $transactionId;
    private array $paymentInfo;

    /**
     * @param StripeClient $stripeClient
     * @param string $transactionId
     * @param array $paymentInfo
     */
    public function __construct(StripeClient $stripeClient, string $transactionId, array $paymentInfo)
    {
        $this->stripeClient = $stripeClient;
        $this->transactionId = $transactionId;
        $this->paymentInfo = $paymentInfo;
    }

    public function execute(): bool
    {
        try {
            $paymentMethodId = $this->stripeClient->paymentMethods->create([
                'type' => 'card',
                'card' => [
                    'number' => $this->paymentInfo['number'],
                    'exp_month' => $this->paymentInfo['expMonth'],
                    'exp_year' => $this->paymentInfo['expYear'],
                    'cvc' => $this->paymentInfo['cvc'],
                ],
            ])->id;

            $response = $this->stripeClient->paymentIntents->confirm($this->transactionId, [
                'payment_method' => $paymentMethodId
            ]);

            if ($response->status === 'succeeded') {
                echo "Payment transaction " . $response->status . ", id: " . $response->id . "\n" . "Amount received: " . $response->amount_received . "\n";
                return true;
            } else {
                echo "Payment transaction " . $response->status . ", id: " . $response->id . "\n";
                return false;
            }
        } catch (\Exception $e) {
            throw new \Exception('Failed to execute transaction: ' . $e->getMessage());
        }
    }
}