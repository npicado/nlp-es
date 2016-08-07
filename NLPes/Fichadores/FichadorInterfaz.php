<?php

namespace NLPes\Fichadores;

use NLPes\Tuberia\TuboInterfaz;

interface FichadorInterfaz extends TuboInterfaz
{
    /**
     * Separa una cadena de texto en una secuencia de fichas
     *
     * @param string $texto El texto a fichar
     * @return array El arreglo de fichas
     */
    public function fichar($texto);
}
