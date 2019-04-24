<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductSetGui;

use Orm\Zed\ProductSet\Persistence\SpyProductSetQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ContentProductSetGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const QUERY_CONTAINER_PRODUCT_SET = 'QUERY_CONTAINER_PRODUCT_SET';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $this->provideProductQueryContainer($container);
        $this->provideLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductQueryContainer(Container $container): void
    {
        $container[static::QUERY_CONTAINER_PRODUCT_SET] = function (Container $container) {
            return SpyProductSetQuery::create();
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideLocaleFacade(Container $container): void
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ContentProductSetGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };
    }
}
