<?php

namespace Felipecwb;

use Closure;
use LogicException;
use BadMethodCallException;

class Container
{
    /**
     * Records
     * @var array
     */
    private $records = [];
    /**
     * loaded Closures
     * @var array
     */
    private $initialized = [];

    /**
     * Set the Stored Data
     * @param [type] $identifier [description]
     * @param [type] $data     [description]
     */
    public function set($identifier, $data)
    {
        $identifier = ucfirst($identifier);

        if ($data instanceof Closure) {
            $this->initialized[$identifier] = false;
        }

        $this->records[$identifier] = $data;
    }

    /**
     * Get the Stored Data
     * @param  string $identifier
     * @return mixed
     */
    public function get($identifier)
    {
        $identifier = ucfirst($identifier);
        if (! array_key_exists($identifier, $this->records)) {
            throw new LogicException("Identifier: " . $identifier . " not exists!");
        }

        // lazy load closures
        if ($this->records[$identifier] instanceof Closure
            && ! $this->initialized[$identifier]
        ) {
            $closure = $this->records[$identifier];
            $this->records[$identifier] = $closure($this);
            $this->initialized[$identifier] = true;
        }

        return $this->records[$identifier];
    }

    /**
     * Same as get method
     * @param  string $identifier
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($identifier, array $arguments)
    {
        if (strpos($identifier, 'get') !== 0 || strlen($identifier) <= 3) {
            throw new BadMethodCallException("Method '{$identifier}' does not exists!");
        }

        $identifier = substr($identifier, 3);
        return $this->get($identifier, $arguments);
    }
}
