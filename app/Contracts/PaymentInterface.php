<?php

namespace App\Contracts;

interface PaymentInterface
{
    
    public function pay(float $amount, float $discount, float $tax): float;
}

