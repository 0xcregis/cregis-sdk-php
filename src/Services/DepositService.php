<?php
namespace Cregis\Services;
use Cregis\Client\BaseClient;

class DepositService extends BaseClient
{
    /**
     * Create address
     */
    public function createAddress(array $bizParams): array
    {
        return $this->callApi('/api/v1/address/create', $bizParams);
    }
    /**
     * Batch create addresses
     */
    public function createAddressBatch(array $bizParams): array
    {
        return $this->callApi('/api/v1/batch/address/create', $bizParams);
    }
    /**
     * Update address information
     */
    public function updateAddress(array $bizParams): array
    {
        return $this->callApi('/api/v1/address/update', $bizParams);
    }
    /**
     * Payout request
     */
    public function payout(array $bizParams): array
    {
        return $this->callApi('/api/v1/payout', $bizParams);
    }
     /**
     * Query payout order information
     */
    public function payoutQuery(array $bizParams): array
    {
        return $this->callApi('/api/v1/payout/query', $bizParams);
    }

     /**
     * Determine whether the address exists
     */
    public function isExists(array $bizParams): array
    {
        return $this->callApi('/api/v1/address/inner', $bizParams);
    }
     /**
     * Check if the address format is valid
     */
    public function isLegal(array $bizParams): array
    {
        return $this->callApi('/api/v1/address/legal', $bizParams);
    }
    /**
     * Query supported coins
     */
    public function getCoins(): array
    {
        return $this->callApi('/api/v1/coins');
    }
    /**
     * Query order detail interface
     * @param array $bizParams Business parameters (e.g. order_id, third_party_id, etc.)
     * @return array API response data
     * @throws \GuzzleHttp\Exception\RequestException
     */
    public function getOrderDetail(array $bizParams): array
    {
        // Call the parent class's common request method, passing in the order query API path (assume the path is "v1/order/detail")
        return $this->callApi('/v1/order/detail', $bizParams);
    }

}