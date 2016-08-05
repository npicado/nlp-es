<?php

include '../vendor/autoload.php';

$textoEs = <<<'EOT'
Si no introduce un registro maestro de servicios, debe registrar un texto breve para describir la posición de servicio.
EOT;

$textoEn = <<<'EOT'
If you do not enter a service master record, you must enter a short text describing the service position.
EOT;

$textoFr = <<<'EOT'
Si vous ne saisissez pas un enregistrement maître de service, vous devez entrer un court texte décrivant la position de service.
EOT;

$textoPt = <<<'EOT'
Se o usuário não entrar um registro mestre de serviços, deverá inserir um texto breve para descrever o item de serviço.
EOT;

$detector = new \NLPes\Idiomas\DetectorIdiomas();

echo "Texto (ES):\n";
var_export($detector->analizar($textoEs));
echo "\n\nTexto (EN):\n";
var_export($detector->analizar($textoEn));
echo "\n\nTexto (FR):\n";
var_export($detector->analizar($textoFr));
echo "\n\nTexto (PT):\n";
var_export($detector->analizar($textoPt));

$contenido = file_get_contents('../NLPes/Idiomas/document_pt.txt');
$detector->entrenar($contenido, 'pt');
echo "\n\nTexto (PT ya entrenado):\n";
var_export($detector->analizar($textoPt));
