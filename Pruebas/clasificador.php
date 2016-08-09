<?php

include '../vendor/autoload.php';

function ngramas($texto, $min_gram_length = 3, $max_gram_length = 3)
{
    if ($max_gram_length < $min_gram_length) {
        return false;
    }

    $ngramas = [];
    $textoLen = mb_strlen($texto, 'UTF-8');

    //BEGIN N-GRAM SIZE LOOP $a

    for ($a = $min_gram_length; $a <= $max_gram_length; $a++) { //BEGIN N-GRAM SIZE LOOP $a

        for ($pos = 0; $pos < $textoLen; $pos++) {  //BEGIN POSITION WITHIN WORD $pos

            if (($pos + $a - 1) < $textoLen) {  //IF THE SUBSTRING WILL NOT EXCEED THE END OF THE WORD

                $ngramas[] = mb_substr($texto, $pos, $a, 'UTF-8');

            }  //END IF THE SUBSTRING WILL NOT EXCEED THE END OF THE WORD

        } //END POSITION WITHIN WORD $pos

    }  //END N-GRAM SIZE LOOP $a

    $ngramas = array_unique($ngramas);

    return $ngramas;
}

//$texto = 'vslmt';
//var_export(ngramas($texto, 2, 2));

$texto_belico = file_get_contents('tema_belico.txt');
$texto_politico = file_get_contents('tema_politico.txt');
$texto_clasificar = file_get_contents('texto_clasificar.txt');

$tuberia = new \NLPes\Tuberia();
$tuberia->asignarFichador(new \NLPes\Fichadores\FichadorNatural());
$tuberia->agregarFiltro(new \NLPes\Filtros\FiltroConvertirMinusculas());
$tuberia->agregarFiltro(new \NLPes\Filtros\FiltroQuitarNumeros());
$tuberia->agregarFiltro(new \NLPes\Filtros\FiltroTranscribirCaracteresHispanos());
$tuberia->agregarFiltro(new \NLPes\Filtros\FiltroQuitarPalabrasVacias());

$texto_belico = $tuberia->analizar($texto_belico);
$texto_belico = implode(' ', $texto_belico['fichas']);

$texto_politico = $tuberia->analizar($texto_politico);
$texto_politico = implode(' ', $texto_politico['fichas']);

$texto_clasificar = $tuberia->analizar($texto_clasificar);
//$texto_clasificar = implode(' ', $texto_clasificar['fichas']);

$clasificador = new \NLPes\Clasificadores\ClasificadorBayesiano();
$clasificador->entrenarIndice($texto_belico, 'belico');
$clasificador->entrenarIndice($texto_politico, 'politica');
var_export($clasificador);

/*

Entiddes de localalidades (paises)
Nombre de Entidades (Ministerio de Salud)



*/
$resultado = $clasificador->clasificar($texto_clasificar['fichas']);
print_r($resultado);

