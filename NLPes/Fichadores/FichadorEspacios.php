<?php

namespace NLPes\Fichadores;

use NLPes\Tuberia\Cola;

class FichadorEspacios implements FichadorInterfaz
{
    /**
     * @inheritdoc
     */
    public function fichar($texto)
    {
        if (!is_string($texto)) {
            throw new \InvalidArgumentException('La entrada debe ser cadena de texto.');
        }

        return preg_split('/[\pZ\pC]+/u', $texto, null, PREG_SPLIT_NO_EMPTY);
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
