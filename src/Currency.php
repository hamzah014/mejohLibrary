<?php

namespace MejohLibrary;
use InvalidArgumentException;

class Currency
{
    private const BASE_URL = 'https://marketdata.tradermade.com';
    private const URI_URL = '/api/v1';
    private const CONVERT_URL = '/convert';
    private const CONVERT_LIVE_URL = '/live';
    private const CURRENCY_LIVE_URL = '/live_currencies_list';
    private const CRYPTO_LIVE_URL = '/live_crypto_list';
    protected $apikey;
    protected $client;

    /**
     * To get API Key - please visit https://marketdata.tradermade.com
     *
     * @param string $apikey API Key for marketData usage.
     * 
     * Example usage:
     * $apikey = "ABCDEF";
     *
     */
    public function __construct($apikey)
    {
        $header = [
            'Accept' => 'application/json'
        ];

        $client = new Client();
        
        $this->client = $client->config(self::BASE_URL, $header);
        $this->apikey = $apikey;

    }

    /**
     * Converts a specified amount from one currency to another.
     *
     * @param string $from The currency code of the source currency (e.g., "USD"). 
     * @param string $to The currency code of the target currency (e.g., "MYR"). 
     * @param float $amount The amount of money to convert from the source currency to the target currency. 
     * 
     * Example usage:
     * $from = "USD"; // Convert from US Dollars
     * $to = "MYR";   // Convert to Malaysian Ringgit
     * $amount = 3000; // Amount in USD to convert
     *
     */
    public function convert($from, $to, $amount)
    {

        $uriurl = self::URI_URL . self::CONVERT_URL;

        $body = [
            'api_key' => $this->apikey,
            'from' => $from,
            'to' => $to,
            'amount' => $amount,
        ];
        
        try {

            // Send a GET request
            $request = $this->client->body($body)
                        ->uriPath($uriurl)
                        ->method('GET')
                        ->request();

            $response = $request->getResponse();
            $status_code = $request->getStatusCode();
        
            // Check if the conversion was successful
            if (isset($response) && $status_code == 200) {

                $data = $response;

                $result = array(
                    'from'=> $data['base_currency'],
                    'to'=> $data['quote_currency'],
                    'amount'=> $amount,
                    'total'=> $data['total'],
                );
 
                return $result;
                
            } else {
                return [
                    'error' => 'Error: IP Api result not found in response.'
                ];
            }
        
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }

    }

    /**
     * List of country currencies.
     *
     */
    public function list()
    {

        $uriurl = self::URI_URL . self::CURRENCY_LIVE_URL;

        $body = [
            'api_key' => $this->apikey
        ];
        
        try {
            // Send a GET request
            $request = $this->client->body($body)
                    ->uriPath($uriurl)
                    ->method('GET')
                    ->request();

            $response = $request->getResponse();
            $status_code = $request->getStatusCode();
        
            // Check if the conversion was successful
            if (isset($response) && $status_code == 200) {

                $data = $response;
 
                return $data['available_currencies'];

            } else {
                return [
                    'error' => 'Error: IP Api result not found in response.'
                ];
            }
        
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
 
    /**
     * List of crypto currencies.
     *
     */
    public function getCrypto()
    {
        $uriurl = self::URI_URL . self::CRYPTO_LIVE_URL;

        $body = [
            'api_key' => $this->apikey
        ];
        
        try {
            // Send a GET request
            $request = $this->client->body($body)
                    ->uriPath($uriurl)
                    ->method('GET')
                    ->request();

            $response = $request->getResponse();
            $status_code = $request->getStatusCode();
        
            // Check if the conversion was successful
            if (isset($response) && $status_code == 200) {

                $data = $response;
 
                return $data['available_currencies'];

            } else {
                return [
                    'error' => 'Error: IP Api result not found in response.'
                ];
            }
        
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Converts currency based on the provided array.
     *
     * @param array $arrayCurrency An array that contain key "from" and "to". 
     * Example:
     * [
     *     'from'=> "MYR",
     *     'to'=> "IDR",
     * ],
     * [
     *     'from'=> "MYR",
     *     'to'=> "USD",
     * ]
     *
    */
    public function conversion(array $arrayCurrency)
    {

        $arrCurrency = $this->combineArray($arrayCurrency);

        $uriurl = self::URI_URL . self::CONVERT_LIVE_URL;

        $body = [
            'api_key' => $this->apikey,
            'currency' => $arrCurrency
        ];
        
        try {
            // Send a GET request
            $request = $this->client->body($body)
                    ->uriPath($uriurl)
                    ->method('GET')
                    ->request();

            $response = $request->getResponse();
            $status_code = $request->getStatusCode();
        
            // Check if the conversion was successful
            if (isset($response) && $status_code == 200) {

                $data = $response;

                // Check if the conversion was successful
                if (isset($data) && isset($data['quotes'])) {
    
                    $result = [];
    
                    foreach ($data['quotes'] as $key => $value) {
    
                        if(isset($value['error'])){
                            
                            $param = [
                                'from_currency' => $arrayCurrency[$key]['from'],
                                'to_currency' => $arrayCurrency[$key]['to'],
                                'error' => $value['message']
                            ];
    
                        }
                        else{
    
                            $param = [
                                'from_currency' => $value['base_currency'],
                                'to_currency' => $value['quote_currency'],
                                'rate' => $value['mid']
                            ];
    
                        }
    
                        array_push($result, $param);
    
                    }
     
                    return $result;
                    
                } else {
                    return [
                        'error' => 'Error: Conversion result not found in response.',
                    ];
                }

            } else {
                return [
                    'error' => 'Error: IP Api result not found in response.'
                ];
            }

        
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Combine array for data.
     * 
     * Return data : MYRIDR,MYRUSD
     *
     */
    private function combineArray($arrayCurrency)
    {
        $arrCurrency = null;

        foreach($arrayCurrency as $curr){
            $arrCurrency .= $curr['from'] . $curr['to'] . ',';
        }

        $arrCurrency = rtrim($arrCurrency, ',');

        return $arrCurrency;

    }

}
