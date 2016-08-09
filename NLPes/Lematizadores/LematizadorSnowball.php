<?php

namespace NLPes\Lematizadores;

class LematizadorSnowball implements LematizadorInterfaz
{
    protected function esVocal($c)
    {
        return ($c == 'a' || $c == 'e' || $c == 'i' || $c == 'o' || $c == 'u' || $c == 'á' || $c == 'é' ||
            $c == 'í' || $c == 'ó' || $c == 'ú');
    }

    protected function proximaPosicionVocal($palabra, $inicio = 0)
    {
        $l = mb_strlen($palabra, 'UTF-8');

        for ($i = $inicio; $i < $l; $i++) {
            if ($this->esVocal($palabra[$i])) {
                return $i;
            }
        }

        return $l;
    }

    protected function proximaPosicionConsonante($palabra, $inicio = 0)
    {
        $l = mb_strlen($palabra, 'UTF-8');

        for ($i = $inicio; $i < $l; $i++) {
            if (!$this->esVocal($palabra[$i])) {
                return $i;
            }
        }

        return $l;
    }

    protected function terminaEn($palabra, $sufijo)
    {
        if (mb_strlen($palabra, 'UTF-8') < mb_strlen($sufijo, 'UTF-8')) {
            return false;
        }

        return (substr($palabra, -mb_strlen($sufijo, 'UTF-8')) == $sufijo);
    }

    protected function terminaEnArreglo($palabra, array $sufijos)
    {
        foreach ($sufijos as $sufijo) {
            if ($this->terminaEn($palabra, $sufijo)) {
                return $sufijo;
            }
        }

        return '';
    }

    protected function removerAcento($palabra)
    {
        return str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $palabra);
    }

    public function lematizar($palabra)
    {
        $l = mb_strlen($palabra, 'UTF-8');
        if ($l <= 2) return $palabra;

        $palabra = mb_strtolower($palabra, 'UTF-8');

        $r1 = $r2 = $rv = $l;
        //R1 is the region after the first non-vowel following a vowel, or is the null region at the end of the word if there is no such non-vowel.
        for ($i = 0; $i < ($l - 1) && $r1 == $l; $i++) {
            if ($this->esVocal($palabra[$i]) && !$this->esVocal($palabra[$i + 1])) {
                $r1 = $i + 2;
            }
        }

        //R2 is the region after the first non-vowel following a vowel in R1, or is the null region at the end of the word if there is no such non-vowel.
        for ($i = $r1; $i < ($l - 1) && $r2 == $l; $i++) {
            if ($this->esVocal($palabra[$i]) && !$this->esVocal($palabra[$i + 1])) {
                $r2 = $i + 2;
            }
        }

        if ($l > 3) {
            if (!$this->esVocal($palabra[1])) {
                // If the second letter is a consonant, RV is the region after the next following vowel
                $rv = $this->proximaPosicionVocal($palabra, 2) + 1;
            } elseif ($this->esVocal($palabra[0]) && $this->esVocal($palabra[1])) {
                // or if the first two letters are vowels, RV is the region after the next consonant
                $rv = $this->proximaPosicionConsonante($palabra, 2) + 1;
            } else {
                //otherwise (consonant-vowel case) RV is the region after the third letter. But RV is the end of the word if these positions cannot be found.
                $rv = 3;
            }
        }

        $r1_txt = substr($palabra, $r1);
        $r2_txt = substr($palabra, $r2);
        $rv_txt = substr($palabra, $rv);

        $palabraOriginal = $palabra;

        // Step 0: Attached pronoun
        $pronoun_suf = array('me', 'se', 'sela', 'selo', 'selas', 'selos', 'la', 'le', 'lo', 'las', 'les', 'los', 'nos');
        $pronoun_suf_pre1 = array('éndo', 'ándo', 'ár', 'ér', 'ír');
        $pronoun_suf_pre2 = array('ando', 'iendo', 'ar', 'er', 'ir');
        $suf = $this->terminaEnArreglo($palabra, $pronoun_suf);
        if ($suf != '') {
            $pre_suff = $this->terminaEnArreglo(substr($rv_txt, 0, -strlen($suf)), $pronoun_suf_pre1);
            if ($pre_suff != '') {
                $palabra = $this->removerAcento(substr($palabra, 0, -strlen($suf)));
            } else {
                $pre_suff = $this->terminaEnArreglo(substr($rv_txt, 0, -strlen($suf)), $pronoun_suf_pre2);
                if ($pre_suff != '' ||
                    ($this->terminaEn($palabra, 'yendo') &&
                        (substr($palabra, -strlen($suf) - 6, 1) == 'u'))
                ) {
                    $palabra = substr($palabra, 0, -strlen($suf));
                }
            }
        }

        if ($palabra != $palabraOriginal) {
            $r1_txt = substr($palabra, $r1);
            $r2_txt = substr($palabra, $r2);
            $rv_txt = substr($palabra, $rv);
        }
        $word_after0 = $palabra;

        if (($suf = $this->terminaEnArreglo($r2_txt, array('anza', 'anzas', 'ico', 'ica', 'icos', 'icas', 'ismo', 'ismos', 'able', 'ables', 'ible', 'ibles', 'ista', 'istas', 'oso', 'osa', 'osos', 'osas', 'amiento', 'amientos', 'imiento', 'imientos'))) != '') {
            $palabra = substr($palabra, 0, -strlen($suf));
        } elseif (($suf = $this->terminaEnArreglo($r2_txt, array('icadora', 'icador', 'icación', 'icadoras', 'icadores', 'icaciones', 'icante', 'icantes', 'icancia', 'icancias', 'adora', 'ador', 'ación', 'adoras', 'adores', 'aciones', 'ante', 'antes', 'ancia', 'ancias'))) != '') {
            $palabra = substr($palabra, 0, -strlen($suf));
        } elseif (($suf = $this->terminaEnArreglo($r2_txt, array('logía', 'logías'))) != '') {
            $palabra = substr($palabra, 0, -strlen($suf)) . 'log';
        } elseif (($suf = $this->terminaEnArreglo($r2_txt, array('ución', 'uciones'))) != '') {
            $palabra = substr($palabra, 0, -strlen($suf)) . 'u';
        } elseif (($suf = $this->terminaEnArreglo($r2_txt, array('encia', 'encias'))) != '') {
            $palabra = substr($palabra, 0, -strlen($suf)) . 'ente';
        } elseif (($suf = $this->terminaEnArreglo($r2_txt, array('ativamente', 'ivamente', 'osamente', 'icamente', 'adamente'))) != '') {
            $palabra = substr($palabra, 0, -strlen($suf));
        } elseif (($suf = $this->terminaEnArreglo($r1_txt, array('amente'))) != '') {
            $palabra = substr($palabra, 0, -strlen($suf));
        } elseif (($suf = $this->terminaEnArreglo($r2_txt, array('antemente', 'ablemente', 'iblemente', 'mente'))) != '') {
            $palabra = substr($palabra, 0, -strlen($suf));
        } elseif (($suf = $this->terminaEnArreglo($r2_txt, array('abilidad', 'abilidades', 'icidad', 'icidades', 'ividad', 'ividades', 'idad', 'idades'))) != '') {
            $palabra = substr($palabra, 0, -strlen($suf));
        } elseif (($suf = $this->terminaEnArreglo($r2_txt, array('ativa', 'ativo', 'ativas', 'ativos', 'iva', 'ivo', 'ivas', 'ivos'))) != '') {
            $palabra = substr($palabra, 0, -strlen($suf));
        }

        if ($palabra != $word_after0) {
            $r1_txt = substr($palabra, $r1);
            $r2_txt = substr($palabra, $r2);
            $rv_txt = substr($palabra, $rv);
        }
        $word_after1 = $palabra;

        if ($word_after0 == $word_after1) {
            // Do step 2a if no ending was removed by step 1.
            if (($suf = $this->terminaEnArreglo($rv_txt, array('ya', 'ye', 'yan', 'yen', 'yeron', 'yendo', 'yo', 'yó', 'yas', 'yes', 'yais', 'yamos'))) != '' && (substr($palabra, -strlen($suf) - 1, 1) == 'u')) {
                $palabra = substr($palabra, 0, -strlen($suf));
            }

            if ($palabra != $word_after1) {
                $r1_txt = substr($palabra, $r1);
                $r2_txt = substr($palabra, $r2);
                $rv_txt = substr($palabra, $rv);
            }
            $word_after2a = $palabra;

            // Do Step 2b if step 2a was done, but failed to remove a suffix.
            if ($word_after2a == $word_after1) {
                if (($suf = $this->terminaEnArreglo($rv_txt, array('en', 'es', 'éis', 'emos'))) != '') {
                    $palabra = substr($palabra, 0, -strlen($suf));
                    if ($this->terminaEn($palabra, 'gu')) {
                        $palabra = substr($palabra, 0, -1);
                    }
                } elseif (($suf = $this->terminaEnArreglo($rv_txt, array('arían', 'arías', 'arán', 'arás', 'aríais', 'aría', 'aréis', 'aríamos', 'aremos', 'ará', 'aré', 'erían', 'erías', 'erán', 'erás', 'eríais', 'ería', 'eréis', 'eríamos', 'eremos', 'erá', 'eré', 'irían', 'irías', 'irán', 'irás', 'iríais', 'iría', 'iréis', 'iríamos', 'iremos', 'irá', 'iré', 'aba', 'ada', 'ida', 'ía', 'ara', 'iera', 'ad', 'ed', 'id', 'ase', 'iese', 'aste', 'iste', 'an', 'aban', 'ían', 'aran', 'ieran', 'asen', 'iesen', 'aron', 'ieron', 'ado', 'ido', 'ando', 'iendo', 'ió', 'ar', 'er', 'ir', 'as', 'abas', 'adas', 'idas', 'ías', 'aras', 'ieras', 'ases', 'ieses', 'ís', 'áis', 'abais', 'íais', 'arais', 'ierais', '  aseis', 'ieseis', 'asteis', 'isteis', 'ados', 'idos', 'amos', 'ábamos', 'íamos', 'imos', 'áramos', 'iéramos', 'iésemos', 'ásemos'))) != '') {
                    $palabra = substr($palabra, 0, -strlen($suf));
                }
            }
        }

        // Always do step 3.
        $r1_txt = substr($palabra, $r1);
        $r2_txt = substr($palabra, $r2);
        $rv_txt = substr($palabra, $rv);

        if (($suf = $this->terminaEnArreglo($rv_txt, array('os', 'a', 'o', 'á', 'í', 'ó'))) != '') {
            $palabra = substr($palabra, 0, -strlen($suf));
        } elseif (($suf = $this->terminaEnArreglo($rv_txt, array('e', 'é'))) != '') {
            $palabra = substr($palabra, 0, -1);
            $rv_txt = substr($palabra, $rv);
            if ($this->terminaEn($rv_txt, 'u') && $this->terminaEn($palabra, 'gu')) {
                $palabra = substr($palabra, 0, -1);
            }
        }

        return $this->removerAcento($palabra);
    }
}
