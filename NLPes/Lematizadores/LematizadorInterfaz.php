<?php
/**
 * Created by PhpStorm.
 * User: Nestor Picado
 * Date: 09/08/2016
 * Time: 05:54 AM
 */

namespace NLPes\Lematizadores;


interface LematizadorInterfaz
{
    /**
     * Elimina los sufijos de flexiones en la palabra
     *
     * @param string $palabra
     * @return string
     */
    public function lematizar($palabra);

    /**
     * @param array $entrada
     * @return array
     */
    public function lematizarArreglo(array $entrada);
}