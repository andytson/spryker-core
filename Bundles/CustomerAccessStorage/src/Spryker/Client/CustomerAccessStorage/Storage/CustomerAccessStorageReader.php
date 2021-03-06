<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessStorage\Storage;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Client\CustomerAccessStorage\Dependency\Client\CustomerAccessStorageToStorageClientInterface;
use Spryker\Client\CustomerAccessStorage\Dependency\Service\CustomerAccessStorageToSynchronizationServiceInterface;
use Spryker\Client\CustomerAccessStorage\Mapper\CustomerAccessStorageMapperInterface;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Shared\CustomerAccessStorage\CustomerAccessStorageConstants;

class CustomerAccessStorageReader implements CustomerAccessStorageReaderInterface
{
    /**
     * @var \Spryker\Client\CustomerAccessStorage\Dependency\Client\CustomerAccessStorageToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\CustomerAccessStorage\Dependency\Service\CustomerAccessStorageToSynchronizationServiceInterface
     */
    protected $synchronizationService;

    /**
     * @var \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    protected $customerAccess;

    /**
     * @var \Spryker\Client\CustomerAccessStorage\Mapper\CustomerAccessStorageMapperInterface
     */
    protected $customerAccessStorageMapper;

    /**
     * @var \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface|null
     */
    protected static $storageKeyBuilder;

    /**
     * @param \Spryker\Client\CustomerAccessStorage\Dependency\Client\CustomerAccessStorageToStorageClientInterface $storageClient
     * @param \Spryker\Client\CustomerAccessStorage\Dependency\Service\CustomerAccessStorageToSynchronizationServiceInterface $synchronizationService
     * @param \Spryker\Client\CustomerAccessStorage\Mapper\CustomerAccessStorageMapperInterface $customerAccessStorageMapper
     */
    public function __construct(
        CustomerAccessStorageToStorageClientInterface $storageClient,
        CustomerAccessStorageToSynchronizationServiceInterface $synchronizationService,
        CustomerAccessStorageMapperInterface $customerAccessStorageMapper
    ) {
        $this->storageClient = $storageClient;
        $this->synchronizationService = $synchronizationService;
        $this->customerAccessStorageMapper = $customerAccessStorageMapper;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnauthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        $this->readCustomerAccess();
        $customerAccessTransfer = new CustomerAccessTransfer();

        foreach ($this->customerAccess->getContentTypeAccess() as $contentTypeAccess) {
            if (!$contentTypeAccess->getIsRestricted()) {
                $customerAccessTransfer->addContentTypeAccess($contentTypeAccess);
            }
        }

        return $customerAccessTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAuthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        $this->readCustomerAccess();

        return $this->customerAccess;
    }

    /**
     * @return string
     */
    protected function generateKey(): string
    {
        $synchronizationDataTransfer = new SynchronizationDataTransfer();

        return $this->getStorageKeyBuilder()->generateKey($synchronizationDataTransfer);
    }

    /**
     * @return \Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface
     */
    protected function getStorageKeyBuilder(): SynchronizationKeyGeneratorPluginInterface
    {
        if (static::$storageKeyBuilder === null) {
            static::$storageKeyBuilder = $this->synchronizationService->getStorageKeyBuilder(CustomerAccessStorageConstants::CUSTOMER_ACCESS_RESOURCE_NAME);
        }

        return static::$storageKeyBuilder;
    }

    /**
     * @return void
     */
    protected function readCustomerAccess(): void
    {
        $unauthenticatedCustomerAccess = $this->storageClient->get($this->generateKey());

        if ($unauthenticatedCustomerAccess === null) {
            $unauthenticatedCustomerAccess = [];
        }

        $this->customerAccess = $this->customerAccessStorageMapper
            ->mapArrayToCustomerAccessTransfer($unauthenticatedCustomerAccess, new CustomerAccessTransfer());
    }
}
