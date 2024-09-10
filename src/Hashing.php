<?php

namespace MejohLibrary;
use InvalidArgumentException;

class Hashing
{
    private const LIST_HASH = [
        'md5'        => 'MD5',
        'sha1'       => 'SHA-1',
        'sha256'     => 'SHA-256',
        'sha512'     => 'SHA-512',
        'ripemd160'  => 'RIPEMD-160',
        'whirlpool'  => 'Whirlpool',
    ];

    /**
     * Get list of hashing that applicable for this function.
     * 
     * @return array Array of hashing which contains code and name.
     *
    */
    public function get(): array
    {
        return self::LIST_HASH;
    }

    /**
     * Generate hashing by determine hashing code and value.
     *
     * @param string $hashCode Pass hashing code.
     * @param string $value Pass value for hashing.
     * 
     * Example usage:
     * $hashCode = 'sha256';
     * $value = '123';
     * Return : "!tToKXgmXojZseJ1qleK3AWQQbi5f#"
     * 
     * @return string Value of hashing.
     *
    */
    public function generate(string $hashCode,string $value): string
    {
        $listHash = $this->get();
        
        // Check if the provided hash code is valid
        if(!array_key_exists($hashCode, $listHash)) {
            throw new InvalidArgumentException("Unsupported hash algorithm: {$hashCode}");
        }

        // Generate the hash
        return hash($hashCode, $value);

    }

    /**
     * Verify hashing by determine hashing code, value and hashing value.
     *
     * @param string $hashCode Pass hashing code.
     * @param string $value Pass value for hashing.
     * @param string $hashToVerify Pass existing hash value for verify.
     * 
     * Example usage:
     * $hashCode = 'sha256';
     * $value = '123';
     * $hashToVerify = '!tToKXgmXojZseJ1qleK3AWQQbi5f#';
     * Return : true
     * 
     * @return bool Result of verify.
     *
    */
    public function verify(string $hashCode, string $value, string $hashToVerify): bool
    {
        $listHash = $this->get();
        
        // Check if the provided hash code is valid
        if (!array_key_exists($hashCode, $listHash)) {
            throw new InvalidArgumentException("Unsupported hash algorithm: {$hashCode}");
        }

        // Generate the hash for the value and compare it with the provided hash
        return hash($hashCode, $value) === $hashToVerify;
    }

}
