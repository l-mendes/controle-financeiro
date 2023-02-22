<?php

namespace App\Enums;

enum CategoryType: string
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
}
