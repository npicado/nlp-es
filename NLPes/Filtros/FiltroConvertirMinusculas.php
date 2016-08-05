<?php

namespace NLPes\Filtros;

class FiltroConvertirMinusculas implements FiltroInterfaz
{
    public function filtrar($ficha)
    {
        return mb_convert_case($ficha, MB_CASE_LOWER, 'UTF-8');
    }
}
