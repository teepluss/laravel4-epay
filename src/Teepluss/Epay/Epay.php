<?php namespace Teepluss\Epay;

use ReflectionClass;

class Epay {

    /**
     * Epay instance.
     *
     * @param  string $adapter
     * @param  array  $arguments
     * @return object
     */
    public static function factory($adapter, $arguments = array())
    {
        $adapter = str_replace('_', ' ', $adapter);
        $adapter = ucwords($adapter);
        $adapter = str_replace(' ', '', $adapter);

        $adapterName = __NAMESPACE__.'\\Adapters\\'.$adapter;

        // Adapter not found.
        if ( ! class_exists($adapterName))
        {
            throw new EpayException('Can\'t load payment adapter "'.$adapterName.'"', 0);
        }

        return new $adapterName($arguments);
    }

}