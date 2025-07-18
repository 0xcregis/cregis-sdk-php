<?php

namespace Cregis\Config;

use Dotenv\Dotenv;

class Config
{
    public static function get(): array
    {
        // Load .env file (compatible with older phpdotenv versions)
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->safeLoad();
 
        // Determine SSL verification switch based on environment variable
        $environment = $_ENV['ENVIRONMENT'] ?: 'pro'; // Default: production environment
        $verifySSL = $environment === 'pro'; // Enable verification in production, disable in development

        return [
            'base_uri' => $_ENV['API_BASE_URI'] ,
            'timeout' => (float)($_ENV['HTTP_TIMEOUT'] ?? 5.0),
            'apikey' => $_ENV['API_KEY'],
            'pid' => $_ENV['PID'],
            'verify' => $verifySSL, // Key: pass SSL verification config
        ];
    }
}