<?php

namespace App\Enums;

enum AuditAction: string
{
    case REGISTER = 'register';
    case SPONSOR_ASSIGN = 'sponsor_assign';
    case COMPANY_JOIN = 'company_join';
    case ELIGIBILITY_RESOLVE = 'eligibility_resolve';
    case PLACEMENT_OVERRIDE = 'placement_override';
    case COMPANY_ACTIVATE = 'company_activate';
    case COMPANY_DEACTIVATE = 'company_deactivate';
    case ADMIN_ACTION = 'admin_action';
}
