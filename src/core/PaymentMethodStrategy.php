<?php

namespace ProjetDesignPattern\core;

interface PaymentMethodStrategy // Strategy
{
    public function execute(): bool;
}