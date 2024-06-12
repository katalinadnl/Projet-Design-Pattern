<?php

namespace PaymentLibrary\Interfaces;

interface NotificationInterface
{
    public function notify(string $message): void;
}
