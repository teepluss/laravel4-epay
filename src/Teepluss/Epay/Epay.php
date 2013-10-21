<?php namespace Teepluss\Epay;

use ReflectionClass;

class Epay {

    /**
     * Factory Instance
     *
     * @access public
     * @static
     * @param string $adapter
     * @param array $params (option)
     * @return object class
     */
    public static function factory($adapter, $arguments = array())
    {
        $adapter = ucwords($adapter);

        $adapterName = __NAMESPACE__.'\\Adapters\\'.$adapter;

        if ( ! class_exists($adapterName))
        {
            throw new EpayException('Can\'t load payment adapter "'.$adapterName.'"', 0);
        }

        return new $adapterName($arguments);
    }

}