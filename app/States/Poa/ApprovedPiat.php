<?php

namespace App\States\Poa;

use App\Models\Poa\Poa;
use App\Models\Poa\PoaActivityPiat;

class ApprovedPiat extends PiatState
{
    public static $name = 'APROBADO';

    public static function color(): string
    {
        return 'bg-success-700';
    }

    public static function label(): string
    {
        return 'APROBADO';
    }

    public function to(): ?PiatState
    {
        return new Pending(new PoaActivityPiat);
    }

    public function isActive(string $state): string
    {
        return $this instanceof $state;
    }
}