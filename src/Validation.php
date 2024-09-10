<?php

namespace MejohLibrary;

class Validation
{

    /**
     * Generate random string for password.
     *
     * @param int $length Set the length of password (default - 12).
     * 
     * Example usage:
     * $length = 30;
     * Return : "!tToKXgmXojZseJ1qleK3AWQQbi5f#"
     * 
     * @return string Random password string.
     *
    */
    public function generatePassword(int $length = 12) : string
    {
        // Define character sets
        $upperChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowerChars = 'abcdefghijklmnopqrstuvwxyz';
        $digits = '0123456789';
        $specialChars = '!@#&*';

        // Combine all character sets
        $allChars = $upperChars . $lowerChars . $digits . $specialChars;
    
        // Ensure password has at least one character from each set
        $password = '';
        $password .= $upperChars[random_int(0, strlen($upperChars) - 1)];
        $password .= $lowerChars[random_int(0, strlen($lowerChars) - 1)];
        $password .= $digits[random_int(0, strlen($digits) - 1)];
        $password .= $specialChars[random_int(0, strlen($specialChars) - 1)];
    
        // Fill the remaining length with random characters from all sets
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }
    
        // Shuffle the password to avoid predictable patterns
        return str_shuffle($password);

    }
    
    /**
     * Validate password.
     *
     * @param string $password Pass the password value for validation.
     * @param int $minLength Set the min length of password (default - 12).
     * 
     * Example usage:
     * $password = "!tToKXgmXojZseJ1qleK3AWQQbi5f#";
     * $minLength = 30;
     * Return : true
     * 
     * @return bool Return result true | false.
     *
    */
    public function validatePassword(string $password, int $minLength = 12) : bool
    {
        // Define the validation criteria
        $hasUppercase = preg_match('/[A-Z]/', $password); // At least one uppercase letter
        $hasLowercase = preg_match('/[a-z]/', $password); // At least one lowercase letter
        $hasDigit     = preg_match('/\d/', $password);    // At least one digit
        $hasSpecial   = preg_match('/[\W_]/', $password); // At least one special character (non-alphanumeric)
        $isLongEnough = strlen($password) >= $minLength;  // Check if the password is long enough
    
        // Check if all conditions are met
        if ($hasUppercase && $hasLowercase && $hasDigit && $hasSpecial && $isLongEnough) {
            return true; // Valid password
        } else {
            return false; // Invalid password
        }
    }

}
