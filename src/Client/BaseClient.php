<?php

namespace Cregis\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Cregis\Config\Config;

class BaseClient
{
    /** @var string API key */
    protected $apiKey;
    protected $pid;

    /** @var Client HTTP client */
    protected $httpClient;

    /**
     * Constructor (receives API key, uses global config)
     * @param string $apiKey
     */
    public function __construct()
    {
        $config = Config::get();
        $this->httpClient = new Client($config);
        $this->pid = $config['pid'];
        $this->apiKey = $config['apikey'];
    }

    /**
     * Common request method (for business classes)
     * @param string $endpoint API path
     * @param array $params Request parameters
     * @return array
     * @throws RequestException
     */
    protected function callApi(string $endpoint, array $params = []): array
    {
        $params = $this->appendPublicParams($params);
        $params['sign'] = $this->generateSign($params);
        try {
            $response = $this->httpClient->post($endpoint, [
                'json' => $params
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw $e;
        }
    }

    /**
     * Common signature generation method
     * @param array $params
     * @return string
     */
    protected function generateSign(array $params): string
    {
        // Filter out empty values and sign parameter (same logic as before)
        $filtered = [];
        foreach ($params as $key => $value) {
            if ($key === 'sign' || $value === null || $value === '') continue;
            $filtered[$key] = (string)$value;
        }

        ksort($filtered);
        $signStr = $this->apiKey . implode('', array_map(function($k, $v) { return $k . $v; }, array_keys($filtered), $filtered));
        return strtolower(md5($signStr));
    }

    /**
     * Add public request parameters
     * @param array $params
     * @return array
     */
    private function appendPublicParams(array $params): array
    {
        return array_merge([
            'pid' => $this->pid,
            'nonce' => $this->generateNonce(),
            'timestamp' => (string)round(microtime(true) * 1000),
        ], $params);
    }

    /**
     * Generate random nonce (same logic as before)
     * @return string
     */
    private function generateNonce(): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($chars), 0, 6);
    }
}