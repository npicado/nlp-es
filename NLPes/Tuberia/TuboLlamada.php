<?php
/**
 * Created by PhpStorm.
 * User: Nestor Picado
 * Date: 07/08/2016
 * Time: 02:01 AM
 */

namespace NLPes\Tuberia;


class TuboLlamada implements TuboInterfaz
{
    /** @var callable */
    public $llamada;

    public function __construct(callable $llamada)
    {
        $this->llamada = $llamada;
    }

    public function __invoke($entrada, Cola $cola)
    {
        $entrada = call_user_func($this->llamada, $entrada);

        // Invocamos el próximo tubo en caso necesario
        if ($proximoTubo = $cola->proximo()) {
            $entrada = $proximoTubo->__invoke($entrada, $cola);
        }

        return $entrada;
    }

}