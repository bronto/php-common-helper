<?php

namespace Bronto\Transfer;

interface Response
{
    const OK = 200;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const CONFLICT = 409;
    const INTERNAL_ERROR = 500;

    public function body();
    public function header($name);
    public function code();
}
