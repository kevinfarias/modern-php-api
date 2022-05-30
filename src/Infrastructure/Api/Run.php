<?php

require __DIR__ . "/../../../vendor/autoload.php";

$server = new \Infrastructure\Api\Server();
$server->run();