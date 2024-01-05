<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Type: string implements HasLabel, HasColor
{
    case INBOUND = 'I';
    case OUTBOUND = 'O';

    public function isInbound(): bool
    {
        return $this === self::INBOUND;
    }

    public function isOutbound(): bool
    {
        return $this === self::OUTBOUND;
    }

    public function getLabelText(): string
    {
        return match ($this) {
            self::INBOUND => 'Entrada',
            self::OUTBOUND => 'Saída'
        };
    }

    public function getTextColor(): string
    {
        return match ($this) {
            self::INBOUND => '#00ff00',
            self::OUTBOUND => '#ff5050'
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::INBOUND => 'Entrada',
            self::OUTBOUND => 'Saída'
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::INBOUND => 'success',
            self::OUTBOUND => 'danger'
        };
    }
}
