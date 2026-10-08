<?php

namespace App\Enum;

enum StatutInscription: string
{
    case Active = 'ACTIVE';
    case Terminee = 'TERMINEE';
    case Annulee = 'ANNULEE';
}
