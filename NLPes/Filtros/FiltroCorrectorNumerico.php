<?php

namespace NLPes\Filtros;

use NLPes\Tuberia\Cola;

class FiltroCorrectorNumerico implements FiltroInterfaz
{
    public function filtrar($entrada)
    {
        if (is_string($entrada)) {
            return preg_replace('/\b([\d,]+)\,([\d]{1,2})\b/', '$1.$2', $entrada);
        } elseif (!is_array($entrada)) {
            throw new \InvalidArgumentException('La entrada debe ser cadena de texto o arreglo.');
        }

        foreach ($entrada as $llave => $valor) {
            $entrada[$llave] = preg_replace('/\b([\d,]+)\,([\d]{1,2})\b/', '$1.$2', $valor);
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
