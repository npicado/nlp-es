<?php

namespace NLPes\Filtros;

use NLPes\Tuberia\Cola;

class FiltroQuitarNumeros implements FiltroInterfaz
{
    public function filtrar($entrada)
    {
        $devolverCadena = false;

        if (is_string($entrada)) {
            $entrada = explode(' ', $entrada);
            $devolverCadena = true;
        } elseif (!is_array($entrada)) {
            throw new \InvalidArgumentException('La entrada debe ser cadena de texto o arreglo.');
        }

        foreach ($entrada as $llave => $valor) {
            if (is_numeric(trim($valor, "[](){}-,.?' \"\t\n\r\0\x0B"))) {
                unset($entrada[$llave]);
            }
        }

        if ($devolverCadena) {
            return implode(' ', $entrada);
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
