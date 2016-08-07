<?php

namespace NLPes\Filtros;

use NLPes\Tuberia\Cola;

class FiltroTranscribirCaracteresHispanos implements FiltroInterfaz
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
            $entrada[$llave] = str_replace([
                'á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä', 'é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë', 'í', 'ì', 'ï', 'î', 'Í', 'Ì',
                'Ï', 'Î', 'ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô', 'ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü', 'ñ', 'Ñ', 'ç', 'Ç',
            ], [
                'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'e', 'e', 'e', 'e', 'E', 'E', 'E', 'E', 'i', 'i', 'i', 'i', 'I', 'I',
                'I', 'I', 'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O', 'u', 'u', 'u', 'u', 'U', 'U', 'U', 'U', 'n', 'N', 'c', 'C',
            ], $valor);
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
