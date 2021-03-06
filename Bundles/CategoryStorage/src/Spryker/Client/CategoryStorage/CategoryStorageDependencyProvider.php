<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage;

use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToLocaleClientBridge;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStorageBridge;
use Spryker\Client\CategoryStorage\Dependency\Service\CategoryStorageToSynchronizationServiceBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

/**
 * @method \Spryker\Client\CategoryStorage\CategoryStorageConfig getConfig()
 */
class CategoryStorageDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return new CategoryStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        });

        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new CategoryStorageToStorageBridge($container->getLocator()->storage()->client());
        });

        $container = $this->addLocaleClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new CategoryStorageToLocaleClientBridge($container->getLocator()->locale()->client());
        });

        return $container;
    }
}
