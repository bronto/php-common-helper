<?php

namespace Bronto\Transfer;

interface Adapter
{
    public function createRequest($method, $uri);
}
