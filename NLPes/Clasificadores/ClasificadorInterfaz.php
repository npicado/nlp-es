<?php

namespace NLPes\Clasificadores;

use NLPes\Tuberia\TuboInterfaz;

interface ClasificadorInterfaz extends TuboInterfaz
{
    /**
     * Clasifica el arreglo de fichas comparando el conteo de
     * sus ngramas contra el indice entrenado.
     *
     * @param array $entrada
     * @return array Lista de clasificaciones con sus probabilidades
     */
    public function clasificar(array $entrada);
}