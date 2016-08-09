<?php

include '../vendor/autoload.php';

$texto = <<<'EOT'
Blv. Orden de Malta Edificio NÂ° 13 Santa Elena, Frente Ã¡ Lemus SimÃºn Antiguo CuscatlÃ¡n La Libertad
Alameda Manuel Enrique Araujo y Av. OlÃ­mpica Centro Comercial Plaza JardÃ­n , Edif. B, Local 4-B San Salvador San Salvador
Viceministro de Economía e Investigación, Enrique Läcs, explicó, a Prensalibre.com que "... 'el café llegará a pagar 1,3 millones
(con 0% de arancel) en 10 años con cuotas de $20'.   ¿Cuando tendrémos que pagar? Nicaragua y costa rica".
EOT;


$ti = microtime(true);

$texto = NLPes\Util::repararUTF8($texto);
//echo preg_replace('/([\d\,]+\.?[\d].)/', '', ' 13 '); exit;

$tuberia = new \NLPes\Tuberia\Tuberia();
$tuberia->acoplar(new \NLPes\filtros\FiltroQuitarPaises());
$tuberia->acoplar(new \NLPes\Fichadores\FichadorNatural());
$tuberia->acoplarGuardado('fichado');
$tuberia->acoplar(new \NLPes\Filtros\FiltroConvertirATexto());
$tuberia->acoplarGuardado('textofichado');
$tuberia->acoplarRecuperacion('fichado');
$tuberia->acoplar(new \NLPes\Filtros\FiltroConvertirMinusculas());
$tuberia->acoplar(new \NLPes\Filtros\FiltroCorrectorNumerico());
$tuberia->acoplar(new \NLPes\Filtros\FiltroQuitarNumeros());
$tuberia->acoplar(new \NLPes\Filtros\FiltroTranscribirCaracteresHispanos());
$tuberia->acoplar(new \NLPes\Filtros\FiltroQuitarPalabrasVacias());

var_export($tuberia->deslizar($texto));

echo "\n" . round(microtime(true) - $ti, 3) . " seg.\n";
