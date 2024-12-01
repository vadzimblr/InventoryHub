<?php

namespace App\Enums;

enum OrderStatusType: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Picking = 'picking';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';
    case Registered = 'registered';
}
