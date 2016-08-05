<?php

namespace NLPes;

use NLPes\Fichadores\FichadorInterfaz;
use NLPes\Filtros\FiltroInterfaz;

class Tuberia
{
    /** @var FichadorInterfaz */
    private $fichador;
    private $filtros = [];

    public function asignarFichador(FichadorInterfaz $fichador)
    {
        $this->fichador = $fichador;
    }

    public function agregarFiltro(FiltroInterfaz $filtro)
    {
        $this->filtros[] = $filtro;
    }

    public function analizar($texto)
    {
        $fichas = $this->fichador->fichar($texto);
        $fichasFiltradas = [];

        if (count($this->filtros)) {
            foreach ($fichas as $ficha) {
                foreach ($this->filtros as $filtro) {
                    $ficha = $filtro->filtrar($ficha);
                }

                if ($ficha !== null && $ficha !== '') {
                    $fichasFiltradas[] = $ficha;
                }
            }
        } else {
            $fichasFiltradas = $fichas;
        }

        return [
            'fichas' => $fichasFiltradas
        ];
    }
}
