<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Plugin\Oms\Condition;

use Spryker\Zed\Oms\Exception\ConditionNotFoundException;

class ConditionCollection implements ConditionCollectionInterface, \ArrayAccess
{

    /**
     * @var \Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface[]
     */
    protected $conditions = [];

    /**
     * @param \Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface $condition
     * @param string $name
     *
     * @return $this
     */
    public function add(ConditionInterface $condition, $name)
    {
        $this->conditions[$name] = $condition;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->conditions[$name]);
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\Oms\Exception\ConditionNotFoundException
     *
     * @return \Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface
     */
    public function get($name)
    {
        if (empty($this->conditions[$name])) {
            throw new ConditionNotFoundException(
                sprintf('Could not find condition "%s". You need to add the needed conditions within your DependencyInjector.', $name)
            );
        }

        return $this->conditions[$name];
    }

    /**
     * @param string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @param string $offset
     *
     * @return null|\Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface
     */
    public function offsetGet($offset)
    {
        return ($this->has($offset)) ? $this->get($offset) : null;
    }

    /**
     * @param string $offset
     * @param \Spryker\Zed\Oms\Communication\Plugin\Oms\Condition\ConditionInterface $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->add($value, $offset);
    }

    /**
     * @param string $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->conditions[$offset]);
    }

}
