<?php

namespace App\Exceptions;

class UserRoleException extends \Exception
{
    public static function noUserRoleAssignment(): UserRoleException
    {
        return new self('No user assignment for this user');
    }
}
