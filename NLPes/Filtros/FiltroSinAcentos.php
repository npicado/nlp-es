<?php

namespace NLPes\Filtros;

class FiltroSinAcentos implements FiltroInterfaz
{
    public function filtrar($ficha)
    {
        return str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], $ficha);
    }
}
