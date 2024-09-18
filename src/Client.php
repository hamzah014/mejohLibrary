<?php

namespace MejohLibrary;
use Exception;

class Client
{
    protected $setting;
    protected $result;
    protected $clientRequest;

    public function __construct()
    {
        $this->setting = array(
            'base_url' => "",
            'header' => [],
            'uri_path' => "",
            'method' => "GET",
            'body' => []
        );

        $this->result = array(
            'header' => [],
            'content' => [],
            'response' => [],
            'code' => 200,
        );

        $this->clientRequest = new ClientRequest();

    }

    private function configHeader(array $headers)
    {
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = ucfirst($key) . ': ' . $value; // Format as "Key: Value"
        }

        return $formattedHeaders;

    }

    public function config(string $base_url, array $headers = [])
    {
        $this->setting['base_url'] = $base_url;
        $this->setting['header'] = $this->configHeader($headers);

        return $this;
    }

    public function uriPath(string $uri_path)
    {
        $this->setting['uri_path'] = $uri_path;
        return $this;
    }

    public function method(string $method)
    {
        $this->setting['method'] = $method;
        return $this;
    }

    public function body(array $body)
    {
        $this->setting['body'] = $body;
        return $this;
    }

    public function request()
    {
        $url = $this->setting['base_url'] . $this->setting['uri_path'];
        $method = $this->setting['method'];
        $headers = $this->setting['header'];
        $bodyData = $this->setting['body'];

        // Initialize the cURL handle
        $ch = curl_init();

        // Set cURL options
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,        // The URL for the request
            CURLOPT_RETURNTRANSFER => true,        // Return the response as a string
            CURLOPT_HEADER         => true,        // Include headers in the response output
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

        // Check if cURL execution failed
        if ($response === false) {
            $error = curl_error($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return ['error' => $error, 'status_code' => $httpCode];
        }

        // Get header size before closing the handle
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Close the cURL handle
        curl_close($ch);

        // Separate headers and body
        $headers = substr($response, 0, $headerSize);  // Extract headers
        $body = substr($response, $headerSize);        // Extract body content

        // Parse response headers into an array
        $headerLines = explode("\r\n", trim($headers));
        $parsedHeaders = [];
        foreach ($headerLines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(': ', $line, 2);
                $parsedHeaders[$key] = $value;
            }
        }

        // Attempt to parse the body as JSON
        $resp = json_decode($body, true);
        $error = null;

        // Check if JSON decoding failed
        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = json_last_error_msg();
        }

        if ($httpCode >= 400) {
            throw new Exception("HTTP Error: " . $error);
        }

        // If the response body contains an error
        if (isset($resp['error'])) {
            throw new Exception($resp['error']);
        }

        // Set JSON-decoded response, status code, and headers
        $this->result['content'] = $body;
        $this->result['response'] = $resp;
        $this->result['header'] = $parsedHeaders;
        $this->result['code'] = $httpCode;

        return $this->clientRequest->build($this->result);

    }

}
