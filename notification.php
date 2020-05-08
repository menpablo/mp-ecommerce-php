<?php

use MercadoPago\SDK;

require 'vendor/autoload.php';

SDK::setAccessToken("APP_USR-6317427424180639-042414-47e969706991d3a442922b0702a0da44-469485398");

$merchant_order = null;

switch($_GET["topic"]) {
    case "payment":
        $payment = MercadoPago\Payment::find_by_id($_GET["id"]);
        // Get the payment and the corresponding merchant_order reported by the IPN.
        $merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
        break;
    case "merchant_order":
        $merchant_order = MercadoPago\MerchantOrder::find_by_id($_GET["id"]);
        break;
}

error_log(json_encode($merchant_order));

