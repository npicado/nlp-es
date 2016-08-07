<?php

namespace NLPes\Tuberia;

interface TuboInterfaz
{
    /**
     * Invoca la función principal de la clase
     * para luego retornar el recorrido de la tubería.
     *
     * @param $entrada
     * @param Cola $cola
     * @return mixed
     */
    public function __invoke($entrada, Cola $cola);
}
