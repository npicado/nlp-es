<?php

namespace NLPes;

class Util
{
    /**
     * @param string $texto
     * @return string
     */
    static public function codificarUTF8($texto)
    {
        if (!is_string($texto)) {
            return $texto;
        }

        $trans = [
            128 => "\xe2\x82\xac", 130 => "\xe2\x80\x9a", 131 => "\xc6\x92", 132 => "\xe2\x80\x9e",
            133 => "\xe2\x80\xa6", 134 => "\xe2\x80\xa0", 135 => "\xe2\x80\xa1", 136 => "\xcb\x86",
            137 => "\xe2\x80\xb0", 138 => "\xc5\xa0", 139 => "\xe2\x80\xb9", 140 => "\xc5\x92",
            142 => "\xc5\xbd", 145 => "\xe2\x80\x98", 146 => "\xe2\x80\x99", 147 => "\xe2\x80\x9c",
            148 => "\xe2\x80\x9d", 149 => "\xe2\x80\xa2", 150 => "\xe2\x80\x93", 151 => "\xe2\x80\x94",
            152 => "\xcb\x9c", 153 => "\xe2\x84\xa2", 154 => "\xc5\xa1", 155 => "\xe2\x80\xba",
            156 => "\xc5\x93", 158 => "\xc5\xbe", 159 => "\xc5\xb8"
        ];

        $textoLen = mb_strlen($texto, '8bit');
        $resultado = '';

        for ($i = 0; $i < $textoLen; $i++) { // recorremos todos los caracteres
            $c = $texto{$i};

            if ($c >= "\xc0") { // debería convertirse a UTF8, si las pruebas internas fallan

                $c2 = $i + 1 >= $textoLen ? "\x00" : $texto{$i + 1};
                $c3 = $i + 2 >= $textoLen ? "\x00" : $texto{$i + 2};
                $c4 = $i + 3 >= $textoLen ? "\x00" : $texto{$i + 3};

                if ($c >= "\xc0" & $c <= "\xdf") { // parece ser UTF8 de 2 bytes
                    if ($c2 >= "\x80" && $c2 <= "\xbf") { // es válido por tanto lo agregamos
                        $resultado .= $c . $c2;
                        $i++;
                    } else { // no es válido por tando lo convertimos y luego lo agregamos
                        $cc1 = (chr(ord($c) / 64) | "\xc0");
                        $cc2 = ($c & "\x3f") | "\x80";
                        $resultado .= $cc1 . $cc2;
                    }
                } elseif ($c >= "\xe0" & $c <= "\xef") { // parece ser UTF8 de 3 bytes
                    if ($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf") { // es válido por tanto lo agregamos
                        $resultado .= $c . $c2 . $c3;
                        $i = $i + 2;
                    } else { // no es válido por tando lo convertimos y luego lo agregamos
                        $cc1 = (chr(ord($c) / 64) | "\xc0");
                        $cc2 = ($c & "\x3f") | "\x80";
                        $resultado .= $cc1 . $cc2;
                    }
                } elseif ($c >= "\xf0" & $c <= "\xf7") { // parece ser UTF8 de 4 bytes
                    if ($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf" && $c4 >= "\x80" && $c4 <= "\xbf") { // es válido por tanto lo agregamos
                        $resultado .= $c . $c2 . $c3 . $c4;
                        $i = $i + 3;
                    } else { // no es válido por tando lo convertimos y luego lo agregamos
                        $cc1 = (chr(ord($c) / 64) | "\xc0");
                        $cc2 = ($c & "\x3f") | "\x80";
                        $resultado .= $cc1 . $cc2;
                    }
                } else { // no se ve como UTF8, pero debería ser convertido
                    $cc1 = (chr(ord($c) / 64) | "\xc0");
                    $cc2 = (($c & "\x3f") | "\x80");
                    $resultado .= $cc1 . $cc2;
                }

            } elseif (($c & "\xc0") == "\x80") { // necesita conversión

                if (isset($trans[ord($c)])) { // usamos conversiones especiales de Windows-1252 según el caso
                    $resultado .= $trans[ord($c)];
                } else {
                    $cc1 = (chr(ord($c) / 64) | "\xc0");
                    $cc2 = (($c & "\x3f") | "\x80");
                    $resultado .= $cc1 . $cc2;
                }

            } else { // no necesita conversión

                $resultado .= $c;

            }
        }

        return $resultado;
    }

    /**
     * @param string $texto
     * @return string
     */
    static public function decodificarUTF8($texto)
    {
        if (!is_string($texto)) {
            return $texto;
        }

        $transLlaves = [
            "\xe2\x82\xac", "\xe2\x80\x9a", "\xc6\x92", "\xe2\x80\x9e", "\xe2\x80\xa6", "\xe2\x80\xa0",
            "\xe2\x80\xa1", "\xcb\x86", "\xe2\x80\xb0", "\xc5\xa0", "\xe2\x80\xb9", "\xc5\x92",
            "\xc5\xbd", "\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\xa2",
            "\xe2\x80\x93", "\xe2\x80\x94", "\xcb\x9c", "\xe2\x84\xa2", "\xc5\xa1", "\xe2\x80\xba",
            "\xc5\x93", "\xc5\xbe", "\xc5\xb8",
        ];

        $transValores = [
            "\x80", "\x82", "\x83", "\x84", "\x85", "\x86", "\x87", "\x88", "\x89", "\x8a", "\x8b", "\x8c",
            "\x8e", "\x91", "\x92", "\x93", "\x94", "\x95", "\x96", "\x97", "\x98", "\x99", "\x9a", "\x9b",
            "\x9c", "\x9e", "\x9f"
        ];

        return utf8_decode(str_replace($transLlaves, $transValores, self::codificarUTF8($texto)));
    }

    /**
     * @param string $texto
     * @return string
     */
    static public function repararUTF8($texto)
    {
        $intento = '';

        while ($intento <> $texto) {
            $intento = $texto;
            $texto = self::codificarUTF8(self::decodificarUTF8($texto));
        }

        return self::codificarUTF8(self::decodificarUTF8($texto));
    }
}
