<?php

namespace App\Enum;

enum Position: string
{
    case Attaquant = 'Attaquant';
    case Defenseur = 'Défenseur';
    case Gardien = 'Gardien';
    case Milieu = 'Milieu';
}
