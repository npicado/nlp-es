<?php

namespace NLPes\Tuberia;

class Tuberia
{
    /** @var Cola */
    protected $cola;
    protected $guardado = [];

    public function __construct()
    {
        $this->cola = new Cola();
    }

    public function acoplar(TuboInterfaz $tubo)
    {
        $this->cola->agregar($tubo);
    }

    /**
     * Guarda el valor de la entrada retornada
     * por el tubo acoplado antes de retener.
     *
     * @param string $nombre
     */
    public function acoplarGuardado($nombre)
    {
        $tubo = new TuboLlamada(function ($entrada) use ($nombre) {
            $this->guardado[$nombre] = $entrada;

            return $entrada;
        });

        $this->cola->agregar($tubo);
    }

    /**
     * Recupera una entrada guardada previamente
     * y la inserta al próximo tubo a deslizar.
     *
     * @param string $nombre
     */
    public function acoplarRecuperacion($nombre)
    {
        $tubo = new TuboLlamada(function () use ($nombre) {
            if (!isset($this->guardado[$nombre])) {
                throw new \InvalidArgumentException("No existe el guardado de datos llamado {$nombre}.");
            }

            return $this->guardado[$nombre];
        });

        $this->cola->agregar($tubo);
    }

    /**
     * Desliza una entrada a traves de los tubos acoplados
     * en la tubería, formando una cola de acciones que
     * procesa la entrada y la reduce.
     *
     * @param mixed $entrada
     * @return array
     */
    public function deslizar($entrada)
    {
        $this->acoplarGuardado('resultado');

        if ($proximo = $this->cola->proximo()) {
            $proximo->__invoke($entrada, $this->cola);
        }

        return $this->guardado;
    }
}
