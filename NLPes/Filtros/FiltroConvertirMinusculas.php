<?php

namespace NLPes\Filtros;

use NLPes\Tuberia\Cola;

class FiltroConvertirMinusculas implements FiltroInterfaz
{
    /**
     * @inheritdoc
     */
    public function filtrar($entrada)
    {
        if (is_string($entrada)) {
            return mb_convert_case($entrada, MB_CASE_LOWER, 'UTF-8');
        } elseif (!is_array($entrada)) {
            throw new \InvalidArgumentException('La entrada debe ser cadena de texto o arreglo.');
        }

        foreach ($entrada as $llave => $valor) {
            $entrada[$llave] = mb_convert_case($valor, MB_CASE_LOWER, 'UTF-8');
        }

        return $entrada;
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
