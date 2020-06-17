<?php
declare(strict_types=1);

namespace App\Finder;

interface SlotFinder
{
    public function isAnySlotAvailable(): bool;
}
