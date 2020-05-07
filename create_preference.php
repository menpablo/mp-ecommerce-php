<?php

require 'vendor/autoload.php';

use MercadoPago\Item;
use MercadoPago\Preference;
use MercadoPago\SDK;

SDK::setAccessToken("APP_USR-6317427424180639-042414-47e969706991d3a442922b0702a0da44-469485398");


$page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$back_urls = array(
    "success" => $page_url."/myContracts?paymentState=succeed",
    "failure" => $page_url."/myContracts?paymentState=failed",
    "pending" => $page_url."/myContracts?paymentState=pending",
);

$preference = createPreference("Test",500,"$$$",$back_urls);
return ['url' => $preference->init_point ];

function createPreference($descripcion,$price,$reference, $back_urls = []){
    $preference = new Preference();

    // Crea un ítem en la preferencia
    $item = new Item();
    $item->title = $descripcion;
    $item->quantity = 1;
    $item->unit_price = $price;
    $item->currency_id = "ARS";

    if(!empty($back_urls)){
        $preference->back_urls = $back_urls;
    }

    $preference->items = array($item);
    $preference->external_reference = $reference;
    $preference->save();
    return $preference;
}