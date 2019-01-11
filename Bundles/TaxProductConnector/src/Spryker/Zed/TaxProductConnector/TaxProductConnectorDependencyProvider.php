<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductBridge;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxBridge;
use Spryker\Zed\TaxProductConnector\Dependency\Service\TaxProductConnectorToTaxServiceBridge;
use Spryker\Zed\TaxProductConnector\Dependency\Service\TaxProductConnectorToTaxServiceInterface;

/**
 * @method \Spryker\Zed\TaxProductConnector\TaxProductConnectorConfig getConfig()
 */
class TaxProductConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT = 'facade product';
    public const FACADE_TAX = 'facade tax';
    public const SERVICE_TAX = 'SERVICE_TAX';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addProductFacade($container);
        $container = $this->addTaxFacade($container);
        $container = $this->addTaxService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new TaxProductConnectorToProductBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTaxFacade(Container $container)
    {
        $container[static::FACADE_TAX] = function (Container $container) {
            return new TaxProductConnectorToTaxBridge($container->getLocator()->tax()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTaxService(Container $container)
    {
        $container[static::SERVICE_TAX] = function (Container $container) {
            return new TaxProductConnectorToTaxServiceBridge($container->getLocator()->tax()->service());
        };

        return $container;
    }
}
