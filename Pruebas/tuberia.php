<?php

include '../vendor/autoload.php';

$texto = <<<'EOT'
Blv. Orden de Malta Edificio NÂ° 13 Santa Elena, Frente Ã¡ Lemus SimÃºn Antiguo CuscatlÃ¡n La Libertad
Alameda Manuel Enrique Araujo y Av. OlÃ­mpica Centro Comercial Plaza JardÃ­n , Edif. B, Local 4-B San Salvador San Salvador
Viceministro de Economía e Investigación, Enrique Läcs, explicó, a Prensalibre.com que "... 'el café llegará a pagar 1,3 millones
(con 0% de arancel) en 10 años con cuotas de $20'.   ¿Cuando tendrémos que pagar?".
EOT;

$ti = microtime(true);

$texto = NLPes\Util::repararUTF8($texto);

$tuberia = new \NLPes\Tuberia();
$tuberia->asignarFichador(new \NLPes\Fichadores\FichadorNatural());
$tuberia->agregarFiltro(new \NLPes\Filtros\FiltroConvertirMinusculas());
$tuberia->agregarFiltro(new \NLPes\Filtros\FiltroCorrectorNumerico());
$tuberia->agregarFiltro(new \NLPes\Filtros\FiltroTranscribirCaracteresHispanos());
$tuberia->agregarFiltro(new \NLPes\Filtros\FiltroQuitarPalabrasVacias());

var_export($tuberia->analizar($texto));

echo "\n" . round(microtime(true) - $ti, 3) . " seg.\n";
