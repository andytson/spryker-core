<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorage;

use Generated\Shared\Transfer\ContentTypeContextTransfer;
use Generated\Shared\Transfer\ExecutedContentStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ContentStorage\ContentStorageFactory getFactory()
 */
class ContentStorageClient extends AbstractClient implements ContentStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ExecutedContentStorageTransfer|null
     */
    public function findContentById(int $idContent, string $localeName): ?ExecutedContentStorageTransfer
    {
        return $this->getFactory()
            ->createContentStorage()
            ->findContentById($idContent, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idContent
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ContentTypeContextTransfer|null
     */
    public function findContentTypeContext(int $idContent, string $localeName): ?ContentTypeContextTransfer
    {
        return $this->getFactory()
            ->createContentStorage()
            ->findContentTypeContext($idContent, $localeName);
    }
}
