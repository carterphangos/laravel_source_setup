<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class UserRoles extends Enum
{
    const ADMIN_ROLE = 0;

    const USER_ROLE = 1;
}
