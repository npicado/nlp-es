<?php

namespace NLPes\Filtros;

use NLPes\Tuberia\TuboInterfaz;

interface FiltroInterfaz extends TuboInterfaz
{
    /**
     * Converite la entrada al formato deseado.
     *
     * @param string $entrada
     * @return string
     */
    public function filtrar($entrada);
}