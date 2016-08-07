<?php

namespace NLPes\Filtros;

use NLPes\Tuberia\Cola;

class FiltroConvertirATexto implements FiltroInterfaz
{
    public function filtrar($entrada)
    {
        if (!is_array($entrada)) {
            throw new \InvalidArgumentException('La entrada debe ser un arreglo.');
        }

        return implode(' ', $entrada);
    }

    /**
     * @inheritdoc
     */
    public function __invoke($entrada, Cola $cola)
    {
        $entrada = $this->filtrar($entrada);

        // Invocamos el próximo tubo en caso necesario
        if ($proximoTubo = $cola->proximo()) {
            $entrada = $proximoTubo->__invoke($entrada, $cola);
        }

        return $entrada;
    }
}
