<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\Dependency\Client;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;

class PersistentCartShareToResourceShareClientBridge implements PersistentCartShareToResourceShareClientInterface
{
    /**
     * @var \Spryker\Client\ResourceShare\ResourceShareClientInterface
     */
    protected $resourceShareClient;

    /**
     * @param \Spryker\Client\ResourceShare\ResourceShareClientInterface $resourceShareClient
     */
    public function __construct($resourceShareClient)
    {
        $this->resourceShareClient = $resourceShareClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return $this->resourceShareClient->generateResourceShare($resourceShareRequestTransfer);
    }
}
