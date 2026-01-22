<?php

namespace App\Services;
use Exception;
use App\Contracts\PaymentInterface;

class StripeService implements PaymentInterface
{
   
    public function pay(float $amount, float $discount, float $tax): float
    {
      
        if ($amount < 0) {
            throw new Exception('Amount cannot be negative');
        }

        if ($discount < 0) {
            throw new Exception('Discount cannot be negative');
        }

        if ($tax < 0) {
            throw new Exception('Tax cannot be negative');
        }

        $result = ($amount * $tax / 100) - $discount;

        return round($result, 2);
    }
}

