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

    public function labelClass(): string
    {
        return match ($this) {
            self::new => "blue",
            self::sent => "purple",
            self::error => "red",
            self::approved => "green",
            self::review => "yellow",
        };
    }
}
