## About FreemoPay Service for PHP/Laravel

FreemoPay PHP is a simple and intuitive package to easily integrate the FreemoPay payment system into your PHP applications.

This package is just a simple class that you can copy/paste into your Laravel project:

You can copy the file `app/services/FreemoPayService.php` into your project and adapt the namespace according to your needs.

Here is an exemple of how to use it:

-   make a payment

```
$freemoPayService = new FreemoPayService();
$freemoPayService->init(
    env('FREEMOPAY_USER'),
    env('FREEMOPAY_PASSWORD'),
    env('FREEMOPAY_URL')
);
$res = $freemoPayService->pay(
    "phone_number",
    "external_id",
    amount
);
```

-   check a payment status

```
$freemoPayService = new FreemoPayService();
$freemoPayService->init(
    env('FREEMOPAY_USER'),
    env('FREEMOPAY_PASSWORD'),
    env('FREEMOPAY_URL')
);
$res = $freemoPayService->checkPaymentStatus("payment_reference");

```
