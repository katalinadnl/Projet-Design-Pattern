<?php

namespace ProjetDesignPattern\Interfaces;

interface Observer
{
    public function update(string $transactionId, string $status);
}
