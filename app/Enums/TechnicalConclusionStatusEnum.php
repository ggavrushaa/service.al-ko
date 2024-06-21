<?php

namespace App\Enums;

enum TechnicalConclusionStatusEnum : string
{
    case new = 'Новий';
    case review = 'Розглядається';
    case accepted = 'Затверджено';


    public function name(): string
    {
        return match ($this) {
            self::new => "Новий",
            self::review => "Розглядається",
            self::accepted => "Затверджено",
        };
    }
}
