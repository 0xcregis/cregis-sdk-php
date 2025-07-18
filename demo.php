<?php

require __DIR__ . '/vendor/autoload.php';
use Cregis\Services\PayoutService;
use Cregis\Services\DepositService;
 
// Parse test interface parameters (compatible with CLI and Web environments)
$action = 'all'; // Default: execute all interfaces
 
// CLI parameter parsing (e.g.: php demo.php --action=payout)
if (PHP_SAPI === 'cli') {
    foreach ($argv as $arg) {
        if (strpos($arg, '--action=') === 0) {
            $action = substr($arg, 9);
        }
    }
} 
// Web parameter parsing (e.g.: demo.php?action=payout)
else {
    $action = $_GET['action'] ?? 'all';
}

// Example: Query supported coins (only when action=querycoins)
if ($action=='querycoins') {
    $depositService = new DepositService();
    try {
        $result = $depositService->getCoins();
        echo "getCoins ：" . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n";
    } catch (\Exception $e) {
        echo "getCoins ：" . $e->getMessage() . "\n";
    }
}
// Check if address exists
/**
 * API response example: {"code":"00000","msg":"ok","data":{"result":true}}
 * Address does not exist for this merchant: {"code":"00000","msg":"ok","data":{"result":false}}
 * Invalid address example: {"code":"W0001","msg":"W0001"}
 */
if ($action=='isexists') {
    $depositService = new DepositService();
    try {
        $result = $depositService->isExists([
            "address" => "TXsmKpEuW7qWnXzJLGP9eDLvWPR2GRn1FS",
            "chain_id" => "195",
        ]);
        echo "isexists ：" . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n";
    } catch (\Exception $e) {
        echo "isexists ：" . $e->getMessage() . "\n";
    }
}
// Check if address is valid
/**
 * API response example: {"code":"00000","msg":"ok","data":{"result":true}}
 * Invalid address example: {"code":"00000","msg":"ok","data":{"result":false}}
 */
if ($action=='islegal') {
    $depositService = new DepositService();
    try {
        $result = $depositService->isLegal([
            "address" => "TXsmKpEuW7qWnXzJLGP9eDLvWPR2GRn1FS",
            "chain_id" => "195",
        ]);
        echo "islegal ：" . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n";
    } catch (\Exception $e) {
        echo "islegal ：" . $e->getMessage() . "\n";
    }
}


// Example: Create address (only when action=deposit)
/**
 * Success response example:  {"code":"00000","msg":"ok","data":{"address":"0x8bb998a6abbf869a9f27f3a7e86559dcc138b1f5"}}
 */
if ($action=='createAddress') {
    $depositService = new DepositService();
    try {
        $result = $depositService->createAddress([
            "callback_url"=> "http://xxxx.com/deposit/callback",
            "chain_id"=> "60",
            "alias"=> "cc",
        ]);
        echo "createAddress: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n";
    } catch (\Exception $e) {
        echo "createAddress-error：" . $e->getMessage() . "\n";
    }
}
// Example: Batch create addresses (only when action=createAddressBatch)
/**
 * Success response example:  {"code":"00000","msg":"ok","data":[{"address":"0x1f67ae9dd0643575294c275fb8fbecdef10105fe"},{"address":"0xeb5aa8ea71fd6242255fa737621fd9df092d6b7a"}]}
 */
if ($action=='createAddressBatch') {
    $depositService = new DepositService();
    try {
        $result = $depositService->createAddressBatch([
            "callback_url"=> "http://xxxx.com/deposit/callback",
            "chain_id"=> "60",
            "alias"=> "cc",
            "number"=> 2,
        ]);
        echo "createAddressBatch: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n";
    } catch (\Exception $e) {
        echo "createAddressBatch-error：" . $e->getMessage() . "\n";
    }
}
// Update address information
/**
 * Success response example:  {"code":"00000","msg":"success","data":null}
 */
if ($action=='updateAddress') {
    $depositService = new DepositService();
    try {
        $result = $depositService->updateAddress([
            "address" => "0x8bb998a6abbf869a9f27f3a7e86559dcc138b1f5",
            "callback_url" =>  "http://xxx1111.com/address/callback",
            "alias" =>  "testcc",
            "status" =>  "0",
        ]);
        echo "updateAddress: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n";
    } catch (\Exception $e) {
        echo "updateAddress-error：" . $e->getMessage() . "\n";
    }
}

 
/**
 * Payout request example
 * Response: payout: {"code":"00000","msg":"ok","data":{"cid":1433845558624257}}
 */
if ($action=='payout') {
    $depositService = new DepositService();
    try {
        $result = $depositService->payout([
            "currency" =>  "195@195",
            "address" =>  "TXsmKpEuW7qWnXzJLGP9eDLvWPR2GRn1FS",
            "amount" =>  "1.1",
            "remark" =>  "payout",
            "third_party_id" =>  "ordersn".time(),
            "callback_url" =>  "http://xxx.com/payout/callback",
        ]);
        echo "payout: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n";
    } catch (\Exception $e) {
        echo "payout-error：" . $e->getMessage() . "\n";
    }
}

/**
 * Payout query example
 * Response: payoutQuery: {"code":"00000","msg":"ok","data":{"pid":1418986101489664,"address":"TXsmKpEuW7qWnXzJLGP9eDLvWPR2GRn1FS","chain_id":"195","token_id":"195","currency":"195@195","amount":"1.1","third_party_id":"ordersn1750299745","remark":"payout","txid":null,"block_time":null,"block_height":null,"memo":null,"status":5}}
 */
if ($action=='payoutQuery') {
    $depositService = new DepositService();
    try {
        $result = $depositService->payoutQuery([
            "cid" =>  "1433845557501953"
        ]);
        echo "payoutQuery: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n";
    } catch (\Exception $e) {
        echo "payoutQuery-error：" . $e->getMessage() . "\n";
    }
}



/**
 * Query supported fiat currencies
 * Example response: queryFiatCurrency: {"code":"00000","msg":"ok","data":[{"currency_code":"CHF","currency_decimals":"2","currency_symbol":"Fr","currency_name":"Swiss Franc"}, ...]}
 */
if ($action=='queryFiatCurrency') {
    $payoutService = new PayoutService();
    try {
        $result = $payoutService->queryFiatCurrency();
        echo "queryFiatCurrency: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n";
    } catch (\Exception $e) {
        echo "queryFiatCurrency-error：" . $e->getMessage() . "\n";
    }
} 
/**
 * Query supported cryptocurrencies
 * Example response: {"code":"00000","msg":"ok","data":[{"token_symbol":"USDT","blockchain":"BNB-BSC","token_decimals":18,"token_name":"USDT-BEP20","logo_url":""}, ...]}
 */
if ($action=='queryCryptoCurrency') {
    $payoutService = new PayoutService();
    try {
        $result = $payoutService->queryCryptoCurrency();
        echo "queryCryptoCurrency: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n";
    } catch (\Exception $e) {
        echo "queryCryptoCurrency-error：" . $e->getMessage() . "\n";
    }
}
/**
 * Create checkout order
 * Example response: createCheckout: {"code":"00000","msg":"ok","data":{...}}
 */
if ($action=='createCheckout') {
    $payoutService = new PayoutService();
    try {
        $result = $payoutService->createCheckout([
            "order_id" => "ordersn-".time(),
            "order_amount" => "5",
            "order_currency" => "USD",
            "callback_url" => "https://callback.com",
            "remark" => "order-remark",
            "payer_id" => "payer001",
            "payer_name" => "payer",
            "payer_email" => "payer@gmail.com",
            "valid_time"=> 60,
            "cancel_url" => "https://cancel.xxxx.com",
            "success_url" => "https://success.xxxxx.com",
            "tokens" => "[\"USDT-TRC20\",\"USDT-BEP20\"]",
            "order_details" => "{\"items\": [{\"item_id\": \"10001\", \"item_name\": \"Product1\",\"item_price\": 123.00,\"item_quantity\": 1,\"price_currency\": \"USD\"},{\"item_id\": \"10002\", \"item_name\": \"Product2\",\"item_price\": 100.00,\"item_quantity\": 2,\"price_currency\": \"USD\"}],\"shopping_cost\": 10.88,\"tax_cost\": 10.00}",
            "sub_merchant" => "{\"sub_merchant_id\": \"submerchant10001\",\"sub_merchant_name\": \"Merchant1\"}"
        ]);
        echo "createCheckout: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n";
    } catch (\Exception $e) {
        echo "createCheckout-error：" . $e->getMessage() . "\n";
    }
}


/**
 * Query order info
 * Example response: queryOrderInfo: {"code":"00000","msg":"ok","data":{...}}
 */
if ($action=='queryOrderInfo') {
    $payoutService = new PayoutService();
    try {
        $result = $payoutService->queryOrderInfo([
            "cregis_id" => "po1433847973863424"
        ]);
        echo "queryOrderInfo: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n";
    } catch (\Exception $e) {
        echo "queryOrderInfo-error：" . $e->getMessage() . "\n";
    }
}



  // Deposit callback example
  if ($action=='depositCallback') {
      $callbackService = new \Cregis\Services\CallbackService();
      // Handle deposit callback (using JSON parameters)
      $depositResponse = $callbackService->callback();
      echo "Deposit callback processing result: " . json_encode($depositResponse) . "\n";
      // For normal cases, you should return the string "success". For testing, we output the parameters for easier viewing.
  }

  // Withdrawal callback example
  if ($action=='withdrawalCallback') {
    $callbackService = new \Cregis\Services\CallbackService();
    $withdrawalResponse = $callbackService->callback();
    echo "Withdrawal callback processing result: " . json_encode($withdrawalResponse) . "\n";
    // For normal cases, you should return the string "success". For testing, we output the parameters for easier viewing.
    
}

  // Payment engine callback example
  if ($action=='paymentEngineCallback') {
    $callbackService = new \Cregis\Services\CallbackService();
    $paymentResponse = $callbackService->callback();
    echo "Payment engine callback processing result: " . json_encode($paymentResponse) . "\n";
    // For normal cases, you should return the string "success". For testing, we output the parameters for easier viewing.
}




