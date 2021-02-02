<?php

require 'vendor/autoload.php';

use MercadoPago\Item;
use MercadoPago\Payer;
use MercadoPago\Preference;
use MercadoPago\SDK;
use Ramsey\Uuid\Uuid;

SDK::setAccessToken("APP_USR-6317427424180639-042414-47e969706991d3a442922b0702a0da44-469485398");
SDK::setIntegratorId("dev_24c65fb163bf11ea96500242ac130004");

$page_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";

$external_reference = 'pablo@flow-int.com';

//no aplican con popup
$back_urls = array(
    "success" => $page_url."/paymentResult.php",
    "failure" => $page_url."/paymentResult.php",
    "pending" => $page_url."/paymentResult.php",
);

$webhookUrl =  $page_url."/notification.php";

$title = $_POST['title'];
$price = $_POST['price'];
$quantity = $_POST['unit'];
$img = $_POST['img'];
$preference = createPreference($title,$price,$external_reference,$quantity,$back_urls,$webhookUrl,$page_url."/assets/".basename($img));
error_log(json_encode($preference));



function createPreference($descripcion,$price,$reference,$quantity, $back_urls = [],$webhookUrl,$img){
    $preference = new Preference();

    $payer = new Payer();
    $payer->name = "Lalo";
    $payer->surname = "Landa";
    $payer->email = "test_user_63274575@testuser.com";
    $payer->phone = array(
        "area_code" => "011",
        "number" => "2222-3333"
    );

    $payer->identification = array(
        "type" => "DNI",
        "number" => "22333444"
    );

    $payer->address = array(
        "street_name" => "Falsa",
        "street_number" => 123,
        "zip_code" => "1111"
    );

    // Crea un ítem en la preferencia
    $item = new Item();

    $item->id = "1234";
    $item->title = $descripcion;
    $item->description = "Dispositivo móvil de Tienda e-commerce";
    $item->quantity = 1;
    $item->unit_price = $price;
    $item->currency_id = "ARS";
    $item->picture_url = $img;

    $preference->payment_methods = array(
        "excluded_payment_methods" => array(
            array("id" => "amex")
        ),
        "excluded_payment_types" => array(
            array("id" => "atm")
        ),
        "installments" => 6
    );

    if(!empty($back_urls)){
        $preference->back_urls = $back_urls;
    }

    $preference->auto_return = "approved";
    $preference->items = array($item);
    $preference->payer = $payer;
    $preference->external_reference = $reference;
    $preference->notification_url = $webhookUrl;
    error_log("Preference " .$preference->auto_return);
    $preference->save();
    return $preference;
}

?>


