<?php

require 'vendor/autoload.php';

error_log("hello, this is a test!");

$request_body = file_get_contents('php://input');
error_log($request_body);

error_log(json_encode($_REQUEST));

