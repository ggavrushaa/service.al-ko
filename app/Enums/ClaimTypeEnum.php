<?php

namespace App\Enums;

enum ClaimTypeEnum : string
{
    case warrantyRepair = 'Гарантия';
    case nonWarrantyRepair = 'НеГарантия';
    case returnOrReplacement = 'ВозвратОбмен';

    public function name(): string
    {
        return match ($this) {
            self::warrantyRepair => "Гарантия",
            self::nonWarrantyRepair => "НеГарантия",
            self::returnOrReplacement => "ВозвратОбмен",
        };
    }
}

