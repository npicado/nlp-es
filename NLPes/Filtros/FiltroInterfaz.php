<?php

namespace NLPes\Filtros;

interface FiltroInterfaz
{
    /**
     * @param string $ficha
     * @return string
     */
    public function filtrar($ficha);
}