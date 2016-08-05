<?php

namespace NLPes\Fichadores;

class FichadorNatural implements FichadorInterfaz
{
    public function fichar($texto)
    {
        // Convertirmos los caracteres no imprimibles en espacios
        $texto = preg_replace('/[\pZ\pC]+/u', ' ', $texto);

        // Convertirmos los caracteres no alfanúmericos repetidos en espacios
        $texto = preg_replace('/[^\w\s]{2,}/ui', '  ', $texto);

        // Eliminamos los caracteres no comunes en las orillas de las palabras
        $texto = preg_replace('/\s+[^\w@$]/ui', ' ', $texto); // al principio
        $texto = preg_replace('/[^\w%]\s+/ui', ' ', $texto); // al final

        // Convertimos los espacios repetidos en espacios simples
        $texto = preg_replace('/[\s]+/', ' ', $texto);

        return explode(' ', trim($texto));
    }
}
