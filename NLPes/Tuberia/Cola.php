<?php

namespace NLPes\Tuberia;

class Cola
{
    protected $cola = [];

    /**
     * Agrega un tubo al final de la cola
     *
     * @param TuboInterfaz $tubo
     *
     * @return Cola
     */
    public function agregar(TuboInterfaz $tubo)
    {
        $this->cola[] = $tubo;

        return $this;
    }

    /**
     * Extrae el proximo tubo de la cola
     *
     * @return TuboInterfaz
     */
    public function proximo()
    {
        return array_shift($this->cola);
    }

    /**
     * Obtiene el próximo tubo de la cola pero sin extraerlo
     *
     * @return TuboInterfaz
     */
    public function asomarse()
    {
        return current($this->cola);
    }
}
