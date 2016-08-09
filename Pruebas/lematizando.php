<?php

include '../vendor/autoload.php';


$lematizador = new \NLPes\Lematizadores\LematizadorSnowball();

echo $lematizador->lematizar('estupidamente'); exit;
