<?php

namespace ProjetDesignPattern\Interfaces;

interface Subject
{
    public function addObserver(Observer $observer);
    public function removeObserver(Observer $observer);
    public function notifyObservers(string $transactionId, string $status);
}
