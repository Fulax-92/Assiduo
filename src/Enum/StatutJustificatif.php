<?php

namespace App\Enum;

enum StatutJustificatif: string
{
    case Depose = 'DEPOSE';
    case Exploite = 'EXPLOITE';
    case Rejete = 'REJETE';
    case Archive = 'ARCHIVE';
}
