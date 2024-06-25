<?php

namespace ProjetDesignPattern\PaymentGateways;

use ProjetDesignPattern\Interfaces\PaymentInterface;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPalPayment implements PaymentInterface
{
    private $apiContext;
    private $paymentId;
    private $payment;
    private $returnUrl;
    private $cancelUrl;

    public function initialize(array $credentials, array $config = [])
    {
        if (isset($credentials['client_id']) && isset($credentials['client_secret'])) {
            $this->apiContext = new ApiContext(
                new OAuthTokenCredential(
                    $credentials['client_id'],
                    $credentials['client_secret']
                )
            );
            $this->apiContext->setConfig([
                'mode' => $config['mode'] ?? 'sandbox', // or 'live' for production
                'http.ConnectionTimeOut' => $config['http.ConnectionTimeOut'] ?? 30,
                'log.LogEnabled' => $config['log.LogEnabled'] ?? true,
                'log.FileName' => $config['log.FileName'] ?? '../PayPal.log',
                'log.LogLevel' => $config['log.LogLevel'] ?? 'FINE'
            ]);

            $this->returnUrl = $config['returnUrl'];
            $this->cancelUrl = $config['cancelUrl'];
        } else {
            throw new \InvalidArgumentException('Client ID and Client Secret are required.');
        }
    }

    public function setReturnUrl(string $url)
    {
        $this->returnUrl = $url;
    }

    public function setCancelUrl(string $url)
    {
        $this->cancelUrl = $url;
    }

    public function createTransaction(float $amount, string $currency, string $description)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amountObj = new Amount();
        $amountObj->setTotal($amount);
        $amountObj->setCurrency($currency);

        $transaction = new Transaction();
        $transaction->setAmount($amountObj);
        $transaction->setDescription($description);

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($this->returnUrl)
                     ->setCancelUrl($this->cancelUrl);

        $payment = new Payment();
        $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

        try {
            $payment->create($this->apiContext);
            $this->paymentId = $payment->getId();
        } catch (\Exception $e) {
            throw new \Exception('Failed to create PayPal transaction: ' . $e->getMessage());
        }
    }

    public function executeTransaction()
    {
        if (!$this->paymentId) {
            throw new \Exception('No transaction created.');
        }

        try {
            $payment = Payment::get($this->paymentId, $this->apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId($_GET['PayerID']);

            $this->payment = $payment->execute($execution, $this->apiContext);
        } catch (\Exception $e) {
            throw new \Exception('Failed to execute PayPal transaction: ' . $e->getMessage());
        }
    }

    public function cancelTransaction()
    {
        throw new \Exception('PayPal does not support direct transaction cancellation through API.');
    }

    public function getTransactionStatus(): string
    {
        if (!$this->payment) {
            throw new \Exception('No transaction created or executed.');
        }

        return $this->payment->getState();
    }
}
