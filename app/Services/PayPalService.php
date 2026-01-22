<?php

namespace App\Services;

use App\Contracts\PaymentInterface;
use Exception;

class PayPalService implements PaymentInterface
{
    
    public function pay(float $amount, float $discount, float $tax): float
    {
        if ($amount < 0) {
            throw new Exception('Amount cannot be negative');
        }

        if ($discount < 0) {
            throw new Exception('Discount cannot be negative');
        }

        if ($discount > $amount) {
            throw new Exception('Discount cannot be greater than amount');
        }

        if ($tax < 0) {
            throw new Exception('Tax cannot be negative');
        }

        $result = ($amount - $discount) * ($tax / 100);

        return round($result, 2);
    }
}

