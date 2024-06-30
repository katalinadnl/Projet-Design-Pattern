<?php

namespace ProjetDesignPattern\core;

abstract class Payment { // Singleton
    private static array $instances = array();
    protected bool $isInit;
    protected function __construct() {
        $this->isInit = false;
    }
    protected function __clone() { }
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): Payment
    {
        $class = static::class;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }

    abstract protected function initialize(array $credentials);
    abstract public function createTransaction(float $amount, Currency $currency, string $description): string;
    abstract public function executeTransaction(string $transactionId, PaymentMethod $paymentMethod, array $paymentInfo): bool;
    abstract public function cancelTransaction(string $transactionId): bool;
    abstract public function getTransactionStatus(string $transactionId): string;
    public function isInitialized(): bool
    {
        return $this->isInit;
    }

}
