# PHP Cregis SDK User Guide

## I. Prerequisites
- PHP version ≥ 7.0
- [Composer](https://getcomposer.org/) installed (dependency management tool)
- Project `.env` file configured

---

## II. SDK Installation
Install the SDK to your project via Composer:
```bash
composer require cregis/cregis-sdk-php:^2.0
```
After installation, Composer will automatically handle dependencies and generate `vendor/autoload.php`, and the framework will automatically load the SDK libraries.

---

## III. Framework Integration Guide

#### Add the following content to the .env file in the root directory
```env
## production or development environment
ENVIRONMENT=production
# API base URL (default value can be modified as needed)
API_BASE_URI=https://t-xxxxxxx.cregis.io
# API key
API_KEY=16d4xxxxxxxxxxxxxxxxxxbd7895f4d
# Project ID
PID=1418xxxxxxxxxxx89664
```

####  Add references where needed
```php
use Cregis\Services\PayoutService;
use Cregis\Services\DepositService;
use Cregis\Services\CallbackService;

```
####  Example of creating an address, other examples can be found in demo.php in the SDK

```php
    public function createAddress()
    {
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
        return 'hello,' ;
    }
```
---

## IV. Notes
- Ensure that the framework's `vendor/autoload.php` is correctly loaded (already handled by mainstream frameworks by default).
- For production environments, set `ENVIRONMENT` to `production` in the `.env` file to avoid using test environment interfaces.
- If the framework does not automatically load the `.env` file (e.g., ThinkPHP), you need to manually call `Dotenv\Dotenv::createImmutable(__DIR__)->load();` to load the configuration.
