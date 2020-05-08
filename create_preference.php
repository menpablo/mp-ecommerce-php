<?php

require 'vendor/autoload.php';

use MercadoPago\Item;
use MercadoPago\Preference;
use MercadoPago\SDK;
use Ramsey\Uuid\Uuid;

SDK::setAccessToken("APP_USR-6317427424180639-042414-47e969706991d3a442922b0702a0da44-469485398");


$page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

$back_urls = array(
    "success" => $page_url."/paymentSucceed.php",
    "failure" => $page_url."/paymentFailed.php",
    "pending" => $page_url."/paymentPending.php",
);

$webhookUrl =  $page_url."/notification.php";

$title = $_POST['title'];
$price = $_POST['price'];
$quantity = $_POST['unit'];
$preference = createPreference($title,$price,Uuid::uuid4()->toString(),$quantity,$back_urls,$webhookUrl);
error_log(json_encode($preference));
header("Location:".$preference->init_point);


function createPreference($descripcion,$price,$reference,$quantity, $back_urls = [],$webhookUrl){
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
    $preference->notification_url = $webhookUrl;
    $preference->save();
    return $preference;
}