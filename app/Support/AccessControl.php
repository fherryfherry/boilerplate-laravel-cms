<?php

namespace App\Support;

class AccessControl
{
    public const GUARD = 'web';

    public const SUPER_ADMIN_ROLE = 'super-admin';

    public const DASHBOARD_VIEW = 'dashboard.view';

    public const USER_VIEW = 'users.view';
    public const USER_CREATE = 'users.create';
    public const USER_UPDATE = 'users.update';
    public const USER_DELETE = 'users.delete';

    public const ROLE_VIEW = 'roles.view';
    public const ROLE_CREATE = 'roles.create';
    public const ROLE_UPDATE = 'roles.update';
    public const ROLE_DELETE = 'roles.delete';

    public static function allPermissions(): array
    {
        return [
            self::DASHBOARD_VIEW,
            self::USER_VIEW,
            self::USER_CREATE,
            self::USER_UPDATE,
            self::USER_DELETE,
            self::ROLE_VIEW,
            self::ROLE_CREATE,
            self::ROLE_UPDATE,
            self::ROLE_DELETE,
        ];
    }

    public static function groupedPermissions(): array
    {
        return [
            'dashboard' => [
                self::DASHBOARD_VIEW,
            ],
            'users' => [
                self::USER_VIEW,
                self::USER_CREATE,
                self::USER_UPDATE,
                self::USER_DELETE,
            ],
            'roles' => [
                self::ROLE_VIEW,
                self::ROLE_CREATE,
                self::ROLE_UPDATE,
                self::ROLE_DELETE,
            ],
        ];
    }

    public static function isSystemRole(string $roleName): bool
    {
        return $roleName === self::SUPER_ADMIN_ROLE;
    }
}
