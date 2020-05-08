<?php

require 'vendor/autoload.php';

use MercadoPago\Item;
use MercadoPago\Payer;
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
$img = $_POST['img'];
$preference = createPreference($title,$price,Uuid::uuid4()->toString(),$quantity,$back_urls,$webhookUrl,$page_url."/assets/".basename($img));
error_log(json_encode($preference));
?>

<form action="/procesar-pago" method="POST">
    <script
        src="https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js"
        data-preference-id="<?php echo $preference->id; ?>">
    </script>
</form>

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


    if(!empty($back_urls)){
        $preference->back_urls = $back_urls;
    }

    $preference->items = array($item);
    $preference->payer = $payer;
    $preference->external_reference = $reference;
    $preference->notification_url = $webhookUrl;
    error_log(json_encode($preference));
    $preference->save();
    return $preference;
}