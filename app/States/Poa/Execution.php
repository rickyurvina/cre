<?php

namespace App\States\Poa;

class Execution extends PoaPhase
{

    public static $name = 'EJECUCIÓN';

    public static function color(): string
    {
        return 'bg-success-700';
    }

    public static function label(): string
    {
        return 'EJECUCIÓN';
    }

    public function isActive(string $state): string
    {
        return $this instanceof $state;
    }
}