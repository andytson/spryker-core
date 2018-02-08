<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Permission;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Permission\Dependency\Plugin\PermissionStoragePluginInterface;
use Spryker\Client\Permission\PermissionExecutor\PermissionExecutor;
use Spryker\Client\Permission\PermissionExecutor\PermissionExecutorInterface;
use Spryker\Client\Permission\PermissionFinder\PermissionFinder;
use Spryker\Client\Permission\PermissionFinder\PermissionFinderInterface;

class PermissionFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Permission\PermissionFinder\PermissionFinderInterface
     */
    public function createPermissionConfigurator(): PermissionFinderInterface
    {
        return new PermissionFinder(
            $this->getPermissionPlugins()
        );
    }

    /**
     * @return \Spryker\Client\Permission\PermissionExecutor\PermissionExecutorInterface
     */
    public function createPermissionExecutor(): PermissionExecutorInterface
    {
        return new PermissionExecutor(
            $this->getPermissionStoragePlugin(),
            $this->createPermissionConfigurator()
        );
    }

    /**
     * @return \Spryker\Client\Permission\Plugin\PermissionPluginInterface[]
     */
    protected function getPermissionPlugins(): array
    {
        return $this->getProvidedDependency(PermissionDependencyProvider::PLUGINS_PERMISSION);
    }

    /**
     * @return \Spryker\Client\Permission\Dependency\Plugin\PermissionStoragePluginInterface
     */
    protected function getPermissionStoragePlugin(): PermissionStoragePluginInterface
    {
        return $this->getProvidedDependency(PermissionDependencyProvider::PLUGIN_PERMISSION_STORAGE);
    }
}