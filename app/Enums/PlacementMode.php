<?php

namespace App\Enums;

enum PlacementMode: string
{
    case STANDARD = 'standard';
    case OVERRIDE = 'override';
    case SPILLOVER = 'spillover';
    case FALLBACK = 'fallback';
}
