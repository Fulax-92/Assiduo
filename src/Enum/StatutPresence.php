<?php

namespace App\Enum;

enum StatutPresence: string
{
    case Present = 'PRESENT';
    case Absent = 'ABSENT';
    case Retard = 'RETARD';
    case Excuse = 'EXCUSE';
}
