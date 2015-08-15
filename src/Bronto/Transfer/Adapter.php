<?php

namespace Bronto\Transfer;

/**
 * Adapater factory to generate new transfer requests
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
interface Adapter
{
    /**
     * Initialize a request with a method and uri
     *
     * @param string $method
     * @param string $uri
     * @return \Bronto\Transfer\Request
     */
    public function createRequest($method, $uri);
}
