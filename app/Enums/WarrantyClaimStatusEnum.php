<?php

namespace App\Enums;

enum WarrantyClaimStatusEnum : string
{
    case new = 'Новий';
    case sent = 'Відправлений';
    case error = 'Помилковий';
    case review = 'Розглядається';
    case approved = 'Затверджено';


    public function name(): string
    {
        return match ($this) {
            self::new => "Новий",
            self::sent => "Відправлений",
            self::error => "Помилковий",
            self::approved => "Затверджено",
            self::review => "Розглядається",
        };
    }
}
