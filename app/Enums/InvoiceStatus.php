<?php

declare(strict_types=1);

namespace App\Enums;

use App\Interfaces\InvoiceStatusInterface;

enum InvoiceStatus: int implements InvoiceStatusInterface
{
    use InvoiceStatusHelper;

    case Pending = 0;
    case Paid    = 1;
    case Void    = 2;
    case Failed  = 3;

    public function toString(): string
    {
        return match ($this) {
            self::Paid   => 'Paid',
            self::Failed => 'Declined',
            self::Void   => 'Void',
            default      => 'Pending'
        };
    }

    public function color(): Color
    {
        return match ($this) {
            self::Paid   => Color::Green,
            self::Failed => Color::Red,
            self::Void   => Color::Gray,
            default      => Color::Orange
        };
    }

    /*
    //The UnitEnum interface is automatically applied to all enumerations by the engine.
    interface UnitEnum
    {
        public static cases(): array
        //UnitEnum::cases — Generates a list of cases on an enum
    }
    */

    /*
    //The BackedEnum interface is automatically applied to backed enumerations by the engine.
    interface BackedEnum extends UnitEnum {
        //Methods
        public static from(int|string $value): static
        //BackedEnum::from — Maps a scalar to an enum instance
        public static tryFrom(int|string $value): ?static
        //BackedEnum::tryFrom — Maps a scalar to an enum instance or null

        //*** Inherited methods ***
        public static UnitEnum::cases(): array
    }
    */
}
