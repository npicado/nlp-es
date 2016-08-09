<?php

include '../vendor/autoload.php';

$temas = [];

if (($gestor = fopen('topics.csv', 'r')) !== false) {

    while (($reg = fgetcsv($gestor, 1000, ",")) !== false) {
        $temas[$reg[0]] = [
            'nombre_es' => NLPes\Util::repararUTF8($reg[2]),
            'nombre_en' => NLPes\Util::repararUTF8($reg[1]),
        ];
    }

    fclose($gestor);
}

var_export($temas);
