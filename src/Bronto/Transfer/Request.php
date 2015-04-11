<?php

namespace Bronto\Transfer;

interface Request
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    public function header($name, $value);
    public function param($name, $value);
    public function query($name, $value);
    public function respond();
}
