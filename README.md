# FreemoPay PHP Service

FreemoPay PHP is a simple and intuitive service class to easily integrate the FreemoPay payment system into your PHP applications.

---

## Features

-   **Make Payments**: Process transactions directly through FreemoPay.
-   **Check Payment Status**: Verify the status of a specific payment.

---

## Requirements

-   PHP 7.4 or higher.
-   FreemoPay credentials (username, password, and API URL).
-   Laravel (completely optional, just that i love it 😆😁).

---

## Installation

### Manual Installation:

1. Copy the file `app/services/FreemoPayService.php` into your project.
2. Adjust the namespace according to your project's structure.

---

## Usage

### 1. Initialize the Service

Before using the service, initialize it with your FreemoPay credentials:

```php
$freemoPayService = new FreemoPayService();
$freemoPayService->init(
    env('FREEMOPAY_USER'),    // Your FreemoPay username
    env('FREEMOPAY_PASSWORD'), // Your FreemoPay password
    env('FREEMOPAY_URL')      // The API base URL
);
```

### 2. Make a Payment

To process a payment using the FreemoPay service, follow this example:

```php
$freemoPayService = new FreemoPayService();
$freemoPayService->init(
    env('FREEMOPAY_USER'),    // Your FreemoPay username
    env('FREEMOPAY_PASSWORD'), // Your FreemoPay password
    env('FREEMOPAY_URL')      // The API base URL
);

$response = $freemoPayService->pay(
    "phone_number", // Payer's phone number
    "external_id",  // Unique identifier for your transaction
    amount          // Amount to pay in the supported currency
);
```

### 3. Check Payment Status

To check the status of a payment, use the following method:

```php
$freemoPayService = new FreemoPayService();
$freemoPayService->init(
    env('FREEMOPAY_USER'),    // Your FreemoPay username
    env('FREEMOPAY_PASSWORD'), // Your FreemoPay password
    env('FREEMOPAY_URL')      // The API base URL
);

$response = $freemoPayService->checkPaymentStatus("payment_reference");
```

### 4. Error Handling

If something goes wrong, the FreemoPayService will throw exceptions. Example:

```php
try {
    $freemoPayService->pay("phone_number", "external_id", amount);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

---

## Contribution

Feel free to contribute by cloning this repository and submitting pull requests. Suggestions and issues are welcome!
