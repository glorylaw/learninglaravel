<?php

namespace App\Enums;

enum Role: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case BRANCH_ADMIN = 'branch_admin';
    case MANAGER = 'manager';

}