<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class BaseColumn extends Enum
{
    const COLOUM_ID = 'id';

    const COLUMN_CREATED = 'created_at';

    const COLUMN_UPDATED = 'updated_at';
}
