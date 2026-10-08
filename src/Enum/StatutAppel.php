<?php

namespace App\Enum;

enum StatutAppel: string
{
    case Brouillon = 'BROUILLON';
    case EnCours = 'EN_COURS';
    case Valide = 'VALIDE';
    case Verrouille = 'VERROUILLE';
    case Annule = 'ANNULE';
}
