<?php

namespace App\Enums;

enum UserType: string
{
    case ADMIN = 'admin';
    case GURU = 'guru';
    case MURID = 'murid';
}
