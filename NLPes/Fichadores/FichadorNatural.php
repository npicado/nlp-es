<?php

namespace NLPes\Fichadores;

use NLPes\Tuberia\Cola;

class FichadorNatural implements FichadorInterfaz
{
    /**
     * @inheritdoc
     */
    public function fichar($texto)
    {
        if (!is_string($texto)) {
            throw new \InvalidArgumentException('La entrada debe ser cadena de texto.');
        }

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

    /**
     * @inheritdoc
     */
    public function __invoke($entrada, Cola $cola)
    {
        $entrada = $this->fichar($entrada);

        // Invocamos el próximo tubo en caso necesario
        if ($proximoTubo = $cola->proximo()) {
            $entrada = $proximoTubo->__invoke($entrada, $cola);
        }

        return $entrada;
    }
}
