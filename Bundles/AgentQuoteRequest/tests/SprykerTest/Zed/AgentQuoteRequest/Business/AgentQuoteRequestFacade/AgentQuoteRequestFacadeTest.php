<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AgentQuoteRequest\Business\AgentQuoteRequestFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteRequestFilterBuilder;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestOverviewFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Zed\AgentQuoteRequest\AgentQuoteRequestConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AgentQuoteRequest
 * @group Business
 * @group AgentQuoteRequestFacade
 * @group Facade
 * @group AgentQuoteRequestFacadeTest
 * Add your own group annotations below this line
 */
class AgentQuoteRequestFacadeTest extends Unit
{
    protected const PAGINATION_MAX_PER_PAGE = 5;
    protected const PAGINATION_PAGE = 1;

    protected const FAKE_QUOTE_REQUEST_REFERENCE = 'FAKE_QUOTE_REQUEST_REFERENCE';

    /**
     * @var \SprykerTest\Zed\AgentQuoteRequest\AgentQuoteRequestBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $customerTransfer = $this->tester->haveCustomer();

        $this->companyUserTransfer = $this->tester->createCompanyUser($customerTransfer);
        $this->quoteTransfer = $this->tester->createQuote(
            $customerTransfer,
            $this->tester->haveProduct()
        );
    }

    /**
     * @return void
     */
    public function testGetQuoteRequestOverviewCollectionRetrievesLastQuoteRequestsWithoutCurrentQuoteRequest(): void
    {
        // Arrange
        (new QuoteRequestCollectionTransfer())
            ->addQuoteRequest($this->createQuoteRequest())
            ->addQuoteRequest($this->createQuoteRequest());

        $quoteRequestOverviewFilterTransfer = (new QuoteRequestOverviewFilterTransfer())
            ->setQuoteRequestReference(null)
            ->setPagination((new PaginationTransfer())->setMaxPerPage(static::PAGINATION_MAX_PER_PAGE)->setPage(static::PAGINATION_PAGE));

        // Act
        $quoteRequestOverviewCollectionTransfer = $this->tester
            ->getFacade()
            ->getQuoteRequestOverviewCollection($quoteRequestOverviewFilterTransfer);

        // Assert
        $this->assertCount(2, $quoteRequestOverviewCollectionTransfer->getQuoteRequests());
        $this->assertNull($quoteRequestOverviewCollectionTransfer->getCurrentQuoteRequest());
    }

    /**
     * @return void
     */
    public function testGetQuoteRequestOverviewCollectionRetrievesLastQuoteRequestsWithFakeCurrentQuoteRequest(): void
    {
        // Arrange
        (new QuoteRequestCollectionTransfer())
            ->addQuoteRequest($this->createQuoteRequest());

        $quoteRequestOverviewFilterTransfer = (new QuoteRequestOverviewFilterTransfer())
            ->setQuoteRequestReference(static::FAKE_QUOTE_REQUEST_REFERENCE)
            ->setPagination((new PaginationTransfer())->setMaxPerPage(static::PAGINATION_MAX_PER_PAGE)->setPage(static::PAGINATION_PAGE));

        // Act
        $quoteRequestOverviewCollectionTransfer = $this->tester
            ->getFacade()
            ->getQuoteRequestOverviewCollection($quoteRequestOverviewFilterTransfer);

        // Assert
        $this->assertCount(1, $quoteRequestOverviewCollectionTransfer->getQuoteRequests());
        $this->assertNull($quoteRequestOverviewCollectionTransfer->getCurrentQuoteRequest());
    }

    /**
     * @return void
     */
    public function testGetQuoteRequestOverviewCollectionRetrievesLastQuoteRequestsWithCurrentQuoteRequest(): void
    {
        // Arrange
        $quoteRequestTransfers = (new QuoteRequestCollectionTransfer())
            ->addQuoteRequest($this->createQuoteRequest())
            ->addQuoteRequest($this->createQuoteRequest())
            ->addQuoteRequest($this->createQuoteRequest())
            ->addQuoteRequest($this->createQuoteRequest())
            ->addQuoteRequest($this->createQuoteRequest())
            ->addQuoteRequest($this->createQuoteRequest())
            ->getQuoteRequests()
            ->getArrayCopy();

        /** @var \Generated\Shared\Transfer\QuoteRequestTransfer $currentQuoteRequestTransfer */
        $currentQuoteRequestTransfer = array_shift($quoteRequestTransfers);

        $quoteRequestOverviewFilterTransfer = (new QuoteRequestOverviewFilterTransfer())
            ->setQuoteRequestReference($currentQuoteRequestTransfer->getQuoteRequestReference())
            ->setPagination((new PaginationTransfer())->setMaxPerPage(static::PAGINATION_MAX_PER_PAGE)->setPage(static::PAGINATION_PAGE));

        // Act
        $quoteRequestOverviewCollectionTransfer = $this->tester
            ->getFacade()
            ->getQuoteRequestOverviewCollection($quoteRequestOverviewFilterTransfer);

        // Assert
        $this->assertCount(static::PAGINATION_MAX_PER_PAGE, $quoteRequestOverviewCollectionTransfer->getQuoteRequests());
        $this->assertEquals(
            $currentQuoteRequestTransfer->getIdQuoteRequest(),
            $quoteRequestOverviewCollectionTransfer->getCurrentQuoteRequest()->getIdQuoteRequest()
        );
    }

    /**
     * @return void
     */
    public function testCancelByReferenceChangesQuoteRequestStatusToCanceled(): void
    {
        // Arrange
        $quoteRequestTransfer = $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );
        $quoteRequestFilterTransfer = (new QuoteRequestFilterBuilder())->build()
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference());

        // Act
        $quoteRequestResponseTransfer = $this->tester
            ->getFacade()
            ->cancelByReference($quoteRequestFilterTransfer);

        // Assert
        $this->assertTrue($quoteRequestResponseTransfer->getIsSuccess());
        $this->assertEquals(
            AgentQuoteRequestConfig::STATUS_CANCELED,
            $quoteRequestResponseTransfer->getQuoteRequest()->getStatus()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function createQuoteRequest(): QuoteRequestTransfer
    {
        return $this->tester->createQuoteRequest(
            $this->tester->createQuoteRequestVersion($this->quoteTransfer),
            $this->companyUserTransfer
        );
    }
}
