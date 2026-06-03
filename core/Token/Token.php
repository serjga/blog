<?php

namespace App\Token;

use App\Session\Session;

class Token
{
    public function generate(): string
    {
        if (!Session::hasTemporary('csrf_token')) {
            Session::setTemporary('csrf_token', bin2hex(random_bytes(32)));
        }
        return Session::getTemporary('csrf_token');
    }

    public function verify($csrfToken): bool
    {
        return hash_equals(Session::getTemporary('csrf_token'), $csrfToken);
    }
}
