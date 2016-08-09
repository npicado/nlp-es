<?php

namespace NLPes\Clasificadores;

use NLPes\Fichadores\FichadorEspacios;
use NLPes\Tuberia\Cola;

class ClasificadorBayesiano implements ClasificadorInterfaz
{
    public $indice = [];
    public $conteo = [];

    protected function generarNgramasPorFichas(array $fichas, $longitudMin = 2, $longitudMax = 3)
    {
        $ngrams = [];
        $fichasConteo = count($fichas);

        if ($longitudMax < $longitudMin) {
            return false;
        }

        for ($a = $longitudMin; $a <= $longitudMax; $a++) {
            for ($pos = 0; $pos < $fichasConteo; $pos++) {
                if (($pos + $a - 1) < $fichasConteo) {
                    $temp = $fichas;
                    $temp = array_splice($temp, $pos, $a);
                    $ngrams[] = implode(' ', $temp);
                }
            }
        }

        return $ngrams;
    }

    /**
     * Agrega conteo de ngramas coincidentes
     *
     * @param string $texto
     * @param string $clasificacion
     */
    public function entrenarIndice($texto, $clasificacion)
    {
        if (!isset($this->conteo[$clasificacion])) {
            $this->conteo[$clasificacion] = 0;
        }

        $fichas = (new FichadorEspacios())->fichar($texto);
        $fichaNgramas = $this->generarNgramasPorFichas($fichas, 2, 3);

        foreach ($fichaNgramas as $fichaNg) {
            if (!isset($this->indice[$fichaNg])) {
                $this->indice[$fichaNg] = [];
            }

            if (!isset($this->indice[$fichaNg][$clasificacion])) {
                $this->indice[$fichaNg][$clasificacion] = 0;
            }

            $this->indice[$fichaNg][$clasificacion]++;
        }

        $this->conteo[$clasificacion] += count($fichaNgramas);
    }

    /**
     * @inheritdoc
     */
    public function clasificar(array $entrada)
    {
        $fichasNgramasConteo = [];

        foreach ($this->generarNgramasPorFichas($entrada, 2, 3) as $ng) {
            if (!isset($fichasNgramasConteo[$ng])) {
                $fichasNgramasConteo[$ng] = 0;
            }

            $fichasNgramasConteo[$ng]++;
        }

        $fichasNgramasSuma = array_sum($fichasNgramasConteo);
        $puntajeFinal = [];

        // Comparamos y contamos los ngramas de cada ficha contra el indice
        foreach ($fichasNgramasConteo as $fichaNg => $fichaNgConteo) {
            if (!isset($this->indice[$fichaNg])) {
                continue;
            }

            foreach ($this->indice[$fichaNg] as $tema => $conteoTema) {
                if (!isset($puntajeFinal[$tema])) {
                    $puntajeFinal[$tema] = 0;
                }

                // Calculamos el puntaje flotante individual y se sumamos al global
                $puntajeFinal[$tema] += ($conteoTema / $this->conteo[$tema]) * ($fichaNgConteo / $fichasNgramasSuma);
            }
        }

        // Ordenamos por puntaje de mayor a menor
        arsort($puntajeFinal);

        return $puntajeFinal;
    }

    /**
     * @inheritdoc
     */
    public function __invoke($entrada, Cola $cola)
    {
        $entrada = $this->clasificar($entrada);

        // Invocamos el próximo tubo en caso necesario
        if ($proximoTubo = $cola->proximo()) {
            $entrada = $proximoTubo->__invoke($entrada, $cola);
        }

        return $entrada;
    }
}
