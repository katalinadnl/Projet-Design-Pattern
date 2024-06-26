<?php

namespace ProjetDesignPattern\Notifications;

use ProjetDesignPattern\Interfaces\Observer;
use ProjetDesignPattern\Interfaces\Subject;

class PaymentNotifier implements Subject
{
    private $observers = [];

    public function addObserver(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    public function removeObserver(Observer $observer)
    {
        $this->observers = array_filter($this->observers, function ($obs) use ($observer) {
            return $obs !== $observer;
        });
    }

    public function notifyObservers(string $transactionId, string $status)
    {
        foreach ($this->observers as $observer) {
            $observer->update($transactionId, $status);
        }
    }
}
