<?php

namespace NLPes\Filtros;

class FiltroTranscribirCaracteresHispanos implements FiltroInterfaz
{
    public function filtrar($ficha)
    {
        return str_replace([
            'á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä',
            'é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë',
            'í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î',
            'ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô',
            'ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü',
            'ñ', 'Ñ', 'ç', 'Ç',
        ], [
            'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A',
            'e', 'e', 'e', 'e', 'E', 'E', 'E', 'E',
            'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I',
            'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O',
            'u', 'u', 'u', 'u', 'U', 'U', 'U', 'U',
            'n', 'N', 'c', 'C',
        ], $ficha);
    }
}
