<?php

namespace ProjetDesignPattern;

require 'vendor/autoload.php';

use ProjetDesignPattern\core\Currency;
use ProjetDesignPattern\core\PaymentMethod;
use ProjetDesignPattern\core\PaymentProviderName;

const STRIPE_API_KEY = 'sk_test_4eC39HqLyjWDarjtT1zdp7dc';

const fakeCreditCard = [
    'number' => '4242424242424242',
    'expMonth' => 12,
    'expYear' => 2027,
    'cvc' => 123
];

function testStripePayment(): bool
{
    $stripePayment = PaymentLoader::getPayment(
        PaymentProviderName::STRIPE,
        [
            'apiKey' => STRIPE_API_KEY
        ]
    );

    try {
        $transactionId = $stripePayment->createTransaction(1337, Currency::USD, 'Test transaction');
        $isSuccess = $stripePayment->executeTransaction($transactionId, PaymentMethod::CARD, fakeCreditCard);
        return $isSuccess;
    } catch (\Exception $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function testGetTransactionStatus(): bool
{
    try {
        $stripePayment = PaymentLoader::getPayment(PaymentProviderName::STRIPE, ['apiKey' => STRIPE_API_KEY]);
        $transactionId = $stripePayment->createTransaction(1337, Currency::USD, 'Test transaction');

        $transactionStatusBeforeExecution = $stripePayment->getTransactionStatus($transactionId);
        if ($transactionStatusBeforeExecution !== 'requires_payment_method') {
            throw new \Exception('Transaction status is not "created" before execution');
        }

        $stripePayment->executeTransaction($transactionId, PaymentMethod::CARD, fakeCreditCard);

        $transactionStatusAfterExecution = $stripePayment->getTransactionStatus($transactionId);
        if ($transactionStatusAfterExecution !== 'succeeded') {
            throw new \Exception('Transaction status is not "succeeded" after execution');
        }

        return true;
    } catch (\Exception $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function testCancelTransaction(): bool
{
    try {
        $stripePayment = PaymentLoader::getPayment(PaymentProviderName::STRIPE, ['apiKey' => STRIPE_API_KEY]);
        $transactionId = $stripePayment->createTransaction(1337, Currency::USD, 'Test transaction');
        $isCancelled = $stripePayment->cancelTransaction($transactionId);
        if (!$isCancelled) {
            throw new \Exception('Transaction was not cancelled');
        }

        $transactionStatus = $stripePayment->getTransactionStatus($transactionId);
        if ($transactionStatus !== 'canceled') {
            throw new \Exception('Transaction status is not "canceled" after cancellation');
        }

        try {
            $stripePayment->executeTransaction($transactionId, PaymentMethod::CARD, fakeCreditCard);
            // should throw error for unsupported operation
            return false;
        } catch (\Exception $e) {
            // should throw error for unsupported operation, this is good !
            return true;
        }
    } catch (\Exception $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function testTemplate(): bool
{
    try {
        // write test code here

        return true; // return true or false, if test is successful
    } catch (\Exception $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function runTest(): void
{
    $logs = [];
    $get_defined_functions = get_defined_functions();
    echo "TESTS STARTED :" . "\n\n";

    foreach ($get_defined_functions['user'] as $functionPath) {
        $functionName = explode("\\", $functionPath)[1];
        if (str_starts_with($functionName, 'test')) {
            echo "----- Running " . $functionName . "()" . " -----\n";
            $result = $functionPath();
            echo "----- Result : " . ($result ? "SUCCESS" : "FAILED") . " -----" . " " . "\n\n";
            $logs[] = [
                'function' => $functionName,
                'result' => $result
            ];
        }
    }
    echo "TESTS FINISHED :" . "\n";
    foreach ($logs as $log) {
        echo $log['function'] . " -> " . ($log['result'] ? "SUCCESS" : "FAILED") . "\n";
    }
}

runTest();