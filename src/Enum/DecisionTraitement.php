<?php

namespace App\Enum;

enum DecisionTraitement: string
{
    case EnAttente = 'EN_ATTENTE';
    case Justifiee = 'JUSTIFIEE';
    case Refusee = 'REFUSEE';
    case SansSuite = 'SANS_SUITE';
}
