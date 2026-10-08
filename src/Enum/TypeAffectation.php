<?php

namespace App\Enum;

enum TypeAffectation: string
{
    case Titulaire = 'TITULAIRE';
    case Remplacant = 'REMPLACANT';
    case CoIntervenant = 'CO_INTERVENANT';
}
