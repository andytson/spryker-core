<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 * @deprecated Use {@link \Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageUrlStoragePublishListener} and `\Spryker\Zed\CmsStorage\Communication\Plugin\Event\Listener\CmsPageUrlStorageUnpublishListener` instead.
 *
 * @method \Spryker\Zed\CmsStorage\Communication\CmsStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsStorage\Persistence\CmsStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsStorage\Business\CmsStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsStorage\CmsStorageConfig getConfig()
 */
class CmsPageUrlStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param array $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $cmsPageIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyUrlTableMap::COL_FK_RESOURCE_PAGE);

        if (empty($cmsPageIds)) {
            return;
        }

        if ($eventName === UrlEvents::ENTITY_SPY_URL_DELETE) {
            $this->getFacade()->unpublish($cmsPageIds);

            return;
        }

        $this->getFacade()->publish($cmsPageIds);
    }
}
