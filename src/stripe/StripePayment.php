<?php
namespace ProjetDesignPattern\stripe;

require 'vendor/autoload.php';


use ProjetDesignPattern\core\Currency;
use ProjetDesignPattern\core\Payment;
use ProjetDesignPattern\core\PaymentMethod;
use Stripe\StripeClient;

class StripePayment extends Payment
{
    private StripeClient|null $stripeClient = null;

    public function initialize(array $credentials): void
    {
        if (!isset($credentials['apiKey'])) {
            throw new \InvalidArgumentException('API key is required.');
        }

        $this->stripeClient = new StripeClient($credentials['apiKey']);
        $this->isInit = true;
        echo "Stripe client initialized\n";
    }

    public function createTransaction(float $amount, Currency $currency, string $description): string
    {
        if ($this->isInit) {
            try {
                $response = $this->stripeClient->paymentIntents->create([
                    'amount' => $amount,
                    'currency' => $currency->value,
                    'description' => $description,
                    'automatic_payment_methods' => ['enabled' => true, 'allow_redirects' => 'never'],
                ]);

                return $response->id;
            } catch (\Exception $e) {
                throw new \Exception('Failed to create transaction: ' . $e->getMessage());
            }
        } else {
            throw new \Exception('Stripe client not initialized.');
        }
    }

    public function executeTransaction(string $transactionId, PaymentMethod $paymentMethod, array $paymentInfo): bool
    {
        if ($this->isInit) {
            switch ($paymentMethod) {
                case PaymentMethod::CARD:
                    $paymentMethodStrategy = new StripeCardPaymentMethodStrategy($this->stripeClient, $transactionId, $paymentInfo);
                    return $paymentMethodStrategy->execute();
                    break;
                case PaymentMethod::BANK_TRANSFER:
                    $paymentMethodStrategy = new StripeBankTransferPaymentMethodStrategy();
                    return $paymentMethodStrategy->execute();
                    break;
                default:
                    throw new \Exception('Payment method not supported.');
            }
        } else {
            throw new \Exception('Stripe client not initialized.');
        }
    }

    public function cancelTransaction(string $transactionId): bool
    {
        if ($this->isInit) {
            try {
                $response = $this->stripeClient->paymentIntents->cancel($transactionId, []);
                echo "Payment intent canceled, id: " . $response->id . "\n" . "Status: " . $response->status . "\n";
                if ($response->status === 'canceled') {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                throw new \Exception('Failed to cancel transaction: ' . $e->getMessage());
            }
        } else {
            throw new \Exception('Stripe client not initialized.');
        }
    }

    public function getTransactionStatus(string $transactionId): string
    {
        if ($this->isInit) {
            try {
                $response = $this->stripeClient->paymentIntents->retrieve($transactionId);
                return $response->status;
            } catch (\Exception $e) {
                throw new \Exception('Failed to retrieve transaction status: ' . $e->getMessage());
            }
        } else {
            throw new \Exception('Stripe client not initialized.');
        }
    }
}
