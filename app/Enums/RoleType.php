<?php

namespace App\Enums;

enum RoleType: string
{
    case Admin = 'admin';
    case Client = 'client';
    case Accountant = 'accountant';
    case AccountManager = 'account-manager';
    case PurchasingManager = 'purchasing-manager';
    case Storekeeper = 'storekeeper';

}
