<?php

namespace MejohLibrary;

class IpLocator
{
    public const BASE_URL = 'http://ip-api.com';
    public const JSON_URI_URL = '/json';
    protected $client;
    protected $info;
    protected $locale;
    protected $currency;
    protected $language;
    protected $countryCode;
    protected $countryData;

    /**
     *
     * @param string $ipAddress Set IP address to get their information.
     * 
     * Example (Singapore IP):
     * $ipAddress = "34.124.137.169";
     *
     */
    public function __construct($ipAddress)
    {
        $header = [
            'Accept' => 'application/json'
        ];
        
        $this->client = new Client(self::BASE_URL, $header);

        $this->locateIp($ipAddress);
        $this->getCountry();
        $this->setLocale();
        $this->setLanguage();
        $this->setCurrency();

    }

    /**
     * To get info of the IP. 
     *  
     * Return : array of info (name,code) 
     *
     */
    public function getInfo()
    {
        return $this->info;
    }
    
    /**
     * To get locale of the IP. 
     *  
     * Return : array of locales 
     *
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * To get currency of the IP. 
     *  
     * Return : 
     * [ 
     *  name => '', 
     *  code => '', 
     * ] 
     *
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * To get language of the IP. 
     *  
     * Return : array of languages 
     *
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * To get all information of the IP. 
     *  
     * Return example (34.124.137.169) :
     * 
     *[country] => Array
     *    (
     *        [name] => Singapore
     *        [code] => SG
     *    )
     *
     *[locales] => Array
     *    (
     *        [0] => zh_Hans_SG
     *        [1] => en_SG
     *    )
     *
     *[languages] => Array
     *    (
     *        [0] => en
     *        [1] => ms
     *        [2] => ta
     *        [3] => zh
     *    )
     *
     *[currency] => Array
     *    (
     *        [name] => Singapore Dollar
     *        [code] => SGD
     *    ) 
     *
    */
    public function getAll()
    {
        $data = [
            'country' => $this->info,
            'locales' => $this->locale,
            'languages' => $this->language,
            'currency' => $this->currency
        ];

        return $data;
    }

    /**
     * Get all country from database json.
     *
     */
    private function getCountry()
    {
        // Path to the JSON file
        $jsonFilePath = __DIR__ . '/data/countries.json';

        // Get the JSON content
        $jsonContent = file_get_contents($jsonFilePath);

        // Decode the JSON content into an associative array
        $countryData = json_decode($jsonContent, true);

        $this->countryData = $countryData;

    }

    /**
     * Converts a specified amount from one currency to another.
     *
     * @param string $ipAddress IP Address to locate the location. 
     * 
     * Example usage:
     * $ipAddress = "8.8.8.8";
     *
     */
    private function locateIp($ipAddress)
    {
        try {

            $uriurl = self::JSON_URI_URL . "/" . $ipAddress;

            // Send a GET request
            $response = $this->client->request($uriurl, 'GET', []);
        
            // Check if the conversion was successful
            if (isset($response) && $response['status_code'] == 200) {

                $data = $response['response'];

                $country = $data['country'];
                $countryCode = $data['countryCode'];
 
                $this->info = [
                    'name'=> $country,
                    'code'=> $countryCode
                ];
                
            } else {
                return [
                    'error' => 'Error: IP Api result not found in response.'
                ];
            }
        
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Set locales of the country.
     *
     */
    private function setLocale()
    {
        try {
            
            $countries = $this->countryData;
            $countryCode = $this->info['code'];
            $locales = null;

            foreach ($countries as $country) {
                if ($country['alpha3'] === $countryCode || $country['alpha2'] === $countryCode) {
                    $locales = $country['locales'];
                }
            }

            $this->locale = $locales;


        } catch (\Throwable $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    /**
     * Set currency of the country.
     *
     */
    private function setCurrency()
    {
        try {
            
            $countries = $this->countryData;
            $countryCode = $this->info['code'];
            $currency = null;

            foreach ($countries as $country) {
                if ($country['alpha3'] === $countryCode || $country['alpha2'] === $countryCode) {
                    $currency = [
                        'name' => $country['currency_name'],
                        'code' => $country['currency']
                    ];
                }
            }

            $this->currency = $currency;


        } catch (\Throwable $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

    /**
     * Set language of the country.
     *
     */
    private function setLanguage()
    {
        try {
            
            $countries = $this->countryData;
            $countryCode = $this->info['code'];
            $languages = null;

            foreach ($countries as $country) {
                if ($country['alpha3'] === $countryCode || $country['alpha2'] === $countryCode) {
                    $languages = $country['languages'];
                }
            }

            $this->language = $languages;


        } catch (\Throwable $e) {
            return [
                'error' => $e->getMessage()
            ];
        }

    }

}
