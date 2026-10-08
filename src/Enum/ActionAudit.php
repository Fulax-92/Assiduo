<?php

namespace App\Enum;

enum ActionAudit: string
{
    case Creation = 'CREATION';
    case Lecture = 'LECTURE';
    case Modification = 'MODIFICATION';
    case Suppression = 'SUPPRESSION';
    case Connexion = 'CONNEXION';
}
