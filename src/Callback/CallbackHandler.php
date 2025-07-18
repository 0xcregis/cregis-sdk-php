<?php

namespace Cregis\Callback;

use Cregis\Client\BaseClient;

class CallbackHandler extends BaseClient
{

    /**
     * Verify callback signature and parse data
     * @param array $callbackData Callback request parameters (including sign)
     * @return array Business data after signature verification
     * @throws \InvalidArgumentException
     */
    public function verifySignature(array $callbackData): array
    {
        // Verify signature
        $calculatedSign = $this->generateSign($callbackData);
        if ($calculatedSign !== $callbackData['sign']) {
            throw new \InvalidArgumentException('Callback signature verification failed');
        }

        // Remove signature parameter and return business data
        unset($callbackData['sign']);
        return $callbackData;
    }
}