<?php

namespace MejohLibrary;
use InvalidArgumentException;

class Math
{
    public function add($a, $b, $decimal)
    {
        $sum = bcadd($a, $b, $decimal);

        return $sum;

    }

    public function substract($a, $b, $decimal)
    {
        $sub = bcsub($a, $b, $decimal);

        return $sub;

    }

    public function multiply($a, $b, $decimal)
    {
        $mult = bcmul($a, $b, $decimal);

        return $mult;

    }

    public function divide($a, $b, $decimal)
    {
        $div = bcdiv($a, $b, $decimal);

        return $div;

    }

    public function remainder($a, $b)
    {
        // Ensure both $a and $b are strings
        $a = (string)$a;
        $b = (string)$b;
    
        // Check if divisor is zero to avoid division by zero error
        if ($b === '0') {
            throw new InvalidArgumentException('Division by zero is not allowed.');
        }
    
        // Calculate the remainder
        $remainder = bcmod($a, $b);
    
        return $remainder;

    }

}
