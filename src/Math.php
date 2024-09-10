<?php

namespace MejohLibrary;
use InvalidArgumentException;

class Math
{

    /**
     * Adds two numbers with a specified decimal precision.
     *
     * @param string $a The first number to add.
     * @param string $b The second number to add.
     * @param int $decimal The number of decimal places to round the result to (default - 2).
     * 
     * Example usage:
     * $a = "1.234";
     * $b = "2.345";
     * $decimal = 2;
     * $result = $this->add($a, $b, $decimal); // Result: "3.58"
     *
     * @return string The sum of the two numbers.
     */
    public function add($a, $b, $decimal = 2)
    {
        $sum = bcadd($a, $b, $decimal);
        return $sum;
    }

    /**
     * Subtracts the second number from the first with a specified decimal precision.
     *
     * @param string $a The number to subtract from.
     * @param string $b The number to subtract.
     * @param int $decimal The number of decimal places to round the result to (default - 2).
     * 
     * Example usage:
     * $a = "5.678";
     * $b = "3.456";
     * $decimal = 2;
     * $result = $this->substract($a, $b, $decimal); // Result: "2.22"
     *
     * @return string The result of the subtraction.
     */
    public function substract($a, $b, $decimal = 2)
    {
        $sub = bcsub($a, $b, $decimal);
        return $sub;
    }

    /**
     * Multiplies two numbers with a specified decimal precision.
     *
     * @param string $a The first number to multiply.
     * @param string $b The second number to multiply.
     * @param int $decimal The number of decimal places to round the result to (default - 2).
     * 
     * Example usage:
     * $a = "1.23";
     * $b = "4.56";
     * $decimal = 2;
     * $result = $this->multiply($a, $b, $decimal); // Result: "5.60"
     *
     * @return string The product of the two numbers.
     */
    public function multiply($a, $b, $decimal = 2)
    {
        $mult = bcmul($a, $b, $decimal);
        return $mult;
    }

    /**
     * Divides the first number by the second with a specified decimal precision.
     *
     * @param string $a The number to be divided.
     * @param string $b The divisor.
     * @param int $decimal The number of decimal places to round the result to (default - 2).
     * 
     * Example usage:
     * $a = "10.50";
     * $b = "2.50";
     * $decimal = 2;
     * $result = $this->divide($a, $b, $decimal); // Result: "4.20"
     *
     * @return string The result of the division.
     */
    public function divide($a, $b, $decimal = 2)
    {
        $div = bcdiv($a, $b, $decimal);
        return $div;
    }

    /**
     * Calculates the remainder of the division of two numbers.
     *
     * @param string $a The number to be divided.
     * @param string $b The divisor.
     * 
     * Example usage:
     * $a = "10";
     * $b = "3";
     * $result = $this->remainder($a, $b); // Result: "1"
     *
     * @throws InvalidArgumentException If division by zero is attempted.
     * @return string The remainder of the division.
     */
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
