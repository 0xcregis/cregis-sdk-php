<?php

namespace Cregis\Services;

use Cregis\Client\BaseClient;
use GuzzleHttp\Exception\RequestException;

class CallbackService extends BaseClient
{
    /**
     * Handle callback
     * @param array $bizParams Business parameters
     * @return array
     * @throws RequestException
     */
    public function callback(array $bizParams=[]):array
    {
        $requestData = [];
        if(!is_array($bizParams) || empty($bizParams)){
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "Error: Invalid JSON format - " . json_last_error_msg() . "\n";
                exit;
            }
            // Validate Content-Type header
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            if (strpos($contentType, 'application/json') === false) {
                echo "Error: Request header must be set to Content-Type: application/json" . "\n";
                exit;
            }
            // Receive JSON request parameters
            $jsonInput = file_get_contents('php://input');
            $requestData = json_decode($jsonInput, true);
        }else{
            $requestData = $bizParams;
        }
        // Configure log directory (auto-create)
        $logDir = __DIR__ . '/../../logs/';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        // Format log content (timestamp + request data)
        $logMessage = sprintf('[%s] Received callback request: %s\n', date('Y-m-d H:i:s'), $jsonInput);
        // Write to log file (split by date)
        $logFile = $logDir . 'callback_' . date('Ymd') . '.log';
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
        
        // Validate callback signature (assume using CallbackHandler)
        $callbackHandler = new \Cregis\Callback\CallbackHandler();
        if (!$callbackHandler->verifySignature($requestData)) {
            return ['status' => 'error', 'message' => 'Invalid signature'];
        }
        return $requestData;
    }
}