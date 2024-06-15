<?php

namespace ProjetDesignPattern\Services;

class TransactionState {
    const PENDING = 'pending';
    const SUCCESS = 'success';
    const FAILED = 'failed';
    const CANCELLED = 'cancelled';

    private $currentState;

    public function __construct() {
        $this->currentState = self::PENDING;
    }

    public function setState($state) {
        $this->currentState = $state;
    }

    public function getState() {
        return $this->currentState;
    }
}
?>
