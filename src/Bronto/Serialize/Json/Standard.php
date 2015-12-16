<?php

namespace Bronto\Serialize\Json;

use Bronto\Serialize\BiDirectional;
use Bronto\Serialize\Exception;

/**
 * Standard PCL bidirectional serializer
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class Standard implements BiDirectional
{
    private static $_enumToMsg = array(
        JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Underflow or the modes mismatch',
        JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
        JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters',
    );

    /**
     * Reads the last json error for parsing or encoding and throws
     * an exception containing the input.
     *
     * @param mixed $thingOrInput
     * @param boolean $encoding
     * @throws \Bronto\Serialize\Exception
     */
    protected function _catchAndThrowError($thingOrInput, $encoding)
    {
        $erroNo = json_last_error();
        if ($erroNo) {
            $error = 'Unknown error';
            if (array_key_exists($erroNo, self::$_enumToMsg)) {
                $error = self::$_enumToMsg[$erroNo];
            }
            throw new Exception($error, $erroNo, $thingOrInput, $encoding);
        }
    }

    /**
     * @see parent
     */
    public function getMimeType()
    {
        return 'application/json';
    }

    /**
     * @see parent
     */
    public function encode($thing)
    {
        $return = json_encode($thing);
        $this->_catchAndThrowError($thing, true);
        return $return;
    }

    /**
     * @see parent
     */
    public function decode($input)
    {
        $return = json_decode($input, true);
        $this->_catchAndThrowError($input, false);
        return $return;
    }
}
