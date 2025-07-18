<?php

namespace Cregis\Services;

use Cregis\Client\BaseClient;

class PayoutService extends BaseClient
{
    /**
     * Query supported fiat currencies
     */
    public function queryFiatCurrency()
    {
        return $this->callApi('/api/v1/order_fiat_currency');
    }
        /**
     * Query supported cryptocurrencies
     */
    public function queryCryptoCurrency()
    {
        return $this->callApi('/api/v1/order_crypto_currency');
    }


    /**
     * Initiate payment request (business-specific method)
     * @param array $bizParams Business parameters (e.g. pid, currency, address, etc.)
     * @return array
     * @throws \GuzzleHttp\Exception\RequestException
     */
    public function createCheckout(array $bizParams): array
    {
        return $this->callApi('/api/v2/checkout', $bizParams);
    }


    public function queryOrderInfo(array $bizParams): array
    {
        return $this->callApi('/api/v2/order/info', $bizParams);
    }
}