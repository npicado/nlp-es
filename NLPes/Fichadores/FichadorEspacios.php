<?php

namespace NLPes\Fichadores;

class FichadorEspacios implements FichadorInterfaz
{
    public function fichar($texto)
    {
        return preg_split('/[\pZ\pC]+/u', $texto, null, PREG_SPLIT_NO_EMPTY);
    }
}
