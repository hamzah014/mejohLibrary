<?php

namespace MejohLibrary;

class Client
{
    
    /**
     * @var string Base URL for the API endpoint.
     */
    protected $base_url;
    
    /**
     * @var string HTTP request method (e.g., GET, POST).
     */
    protected $request_type;
    
    /**
     * @var array Array of HTTP headers.
     */
    protected $headers;

    /**
     * Constructor to initialize the HttpClient with base URL, request type, and headers.
     *
     * @param string $base_url Base URL for the API endpoint.
     * @param string $request_type HTTP request method (POST/GET) (default is 'GET').
     * @param array $headers Array of HTTP headers (default is an empty array).
     * 
     */
    public function __construct($base_url, $request_type = 'GET', $headers = [])
    {
        $this->base_url = $base_url;
        $this->request_type = $request_type;

        $this->configHeader($headers);

    }

    /**
     * Configures HTTP headers for the request.
     *
     * @param array $headers Array of HTTP headers where keys are header names and values are header values.
     */
    private function configHeader($headers)
    {
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = ucfirst($key) . ': ' . $value; // Format as "Key: Value"
        }

        $this->headers = $formattedHeaders;

    }

    /**
     * Executes an HTTP request to the specified URI with optional body data.
     *
     * @param string $uri_interface The URI or endpoint to send the request to.
     * @param array $bodyData Data to send in the request body (for POST requests) or query parameters (for GET requests).
     *
     * @return array Response or error information, including the response body and HTTP status code.
     */
    public function request($uri_interface,$bodyData)
    {
        $url = $this->base_url . $uri_interface;
        $method = $this->request_type;
        $headers = $this->headers;
        
        // Initialize the cURL handle
        $ch = curl_init();
        
        // Common cURL options for all methods
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,        // The URL for the request
            CURLOPT_RETURNTRANSFER => true,        // Return the response as a string
            CURLOPT_FOLLOWLOCATION => true,        // Follow redirects
            CURLOPT_ENCODING       => 'gzip',      // Compress the response (Gzip)
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_2_0,  // Use HTTP/2 for performance
            CURLOPT_CONNECTTIMEOUT => 10,          // Connection timeout
            CURLOPT_TIMEOUT        => 30,          // Overall timeout
            CURLOPT_TCP_KEEPALIVE  => 1,           // Reuse connections
            CURLOPT_HTTPHEADER     => array_merge(['Connection: keep-alive'], $headers), // Custom headers + keep-alive
        ]);
        
        // Handle GET/POST methods
        if (strtoupper($method) === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true); // Set method to POST
            
            if (!empty($bodyData)) {
                // Set the POST fields if data is provided (as a JSON string for simplicity)
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($bodyData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, ['Content-Type: application/json']));
            }
        } elseif (strtoupper($method) === 'GET' && !empty($bodyData)) {
            // For GET, append query parameters to the URL
            $urlWithParams = $url . '?' . http_build_query($bodyData);
            curl_setopt($ch, CURLOPT_URL, $urlWithParams);
        }
        
        // Execute the request
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        // Close the cURL handle
        curl_close($ch);
        
        // Return response or error information
        if ($error) {
            return ['error' => $error, 'status_code' => $httpCode];
        }
    
        return ['response' => $response, 'status_code' => $httpCode];

    }

}
