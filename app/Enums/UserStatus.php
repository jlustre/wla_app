<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::PENDING => 'Pending',
            self::SUSPENDED => 'Suspended',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::ACTIVE => 'bg-green-100 text-green-700',
            self::INACTIVE => 'bg-slate-100 text-slate-500',
            self::PENDING => 'bg-amber-100 text-amber-700',
            self::SUSPENDED => 'bg-rose-100 text-rose-700',
        };
    }
}
