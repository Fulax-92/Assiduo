<?php

namespace App\Enum;

enum SourceJustificatif: string
{
    case Parent = 'PARENT';
    case Eleve = 'ELEVE';
    case Administration = 'ADMINISTRATION';
}
