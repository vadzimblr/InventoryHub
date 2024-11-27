<?php

namespace App\Enums;

enum RoleType: string
{
    case Admin = 'admin';
    case Client = 'client';
    case Accountant = 'accountant';
    case AccountManager = 'account-manager';
    case ProcurementManager = 'procurement-manager';
    case Storekeeper = 'storekeeper';

}
