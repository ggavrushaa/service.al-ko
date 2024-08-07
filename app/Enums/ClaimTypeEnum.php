<?php

namespace App\Enums;

enum ClaimTypeEnum : string
{
    case warrantyRepair = 'Гарантійний ремонт';
    case nonWarrantyRepair = 'Негарантійний ремонт';
    case returnOrReplacement = 'Повернення/заміна';

    public function name(): string
    {
        return match ($this) {
            self::warrantyRepair => "Гарантійний ремонт",
            self::nonWarrantyRepair => "Негарантійний ремонт",
            self::returnOrReplacement => "Повернення/заміна",
        };
    }
}

