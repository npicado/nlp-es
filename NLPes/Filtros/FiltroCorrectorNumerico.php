<?php

namespace NLPes\Filtros;

class FiltroCorrectorNumerico implements FiltroInterfaz
{
    public function filtrar($ficha)
    {
        return preg_replace('/^([\d,]+)\,([\d]{1,2})$/', '$1.$2', $ficha);
    }
}
