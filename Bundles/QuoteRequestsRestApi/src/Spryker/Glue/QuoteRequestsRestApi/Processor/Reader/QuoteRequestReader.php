<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\QuoteRequestsRestApi\Processor\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface;
use Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface;

class QuoteRequestReader implements QuoteRequestReaderInterface
{
    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface
     */
    private $quoteRequestClient;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface
     */
    private $quoteRequestsRestResponseBuilder;

    /**
     * @var \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface
     */
    private $quoteRequestsRequestMapper;

    /**
     * @param \Spryker\Glue\QuoteRequestsRestApi\Dependency\Client\QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestsRestApiToQuoteRequestClient
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\RestResponseBuilder\QuoteRequestsRestResponseBuilderInterface $quoteRequestsRestResponseBuilder
     * @param \Spryker\Glue\QuoteRequestsRestApi\Processor\Mapper\QuoteRequestsRequestMapperInterface $quoteRequestsRequestMapper
     */
    public function __construct(
        QuoteRequestsRestApiToQuoteRequestClientInterface $quoteRequestsRestApiToQuoteRequestClient,
        QuoteRequestsRestResponseBuilderInterface $quoteRequestsRestResponseBuilder,
        QuoteRequestsRequestMapperInterface $quoteRequestsRequestMapper
    ) {
        $this->quoteRequestClient = $quoteRequestsRestApiToQuoteRequestClient;
        $this->quoteRequestsRestResponseBuilder = $quoteRequestsRestResponseBuilder;
        $this->quoteRequestsRequestMapper = $quoteRequestsRequestMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getQuoteRequest(RestRequestInterface $restRequest): RestResponseInterface
    {
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser());
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($restRequest->getResource()->getId())
            ->setCompanyUser($companyUserTransfer);

        $quoteRequestResponseTransfer = $this->quoteRequestClient
            ->getQuoteRequest($quoteRequestFilterTransfer);

        if (
            !$quoteRequestResponseTransfer->getIsSuccessful()
            || $quoteRequestResponseTransfer->getQuoteRequest() === null
        ) {
            return $this->quoteRequestsRestResponseBuilder->createQuoteRequestNotFoundErrorResponse();
        }

        return $this->quoteRequestsRestResponseBuilder
            ->createQuoteRequestRestResponse($quoteRequestResponseTransfer->getQuoteRequest());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getQuoteRequestCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser());
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setCompanyUser($companyUserTransfer);

        if ($restRequest->getPage() !== null) {
            $quoteRequestFilterTransfer->setPagination(
                (new PaginationTransfer())
                    ->setMaxPerPage($restRequest->getPage()->getLimit())
                    ->setPage(($restRequest->getPage()->getOffset() / $restRequest->getPage()->getLimit()) + 1)
            );
        }

        $quoteRequestCollectionTransfer = $this->quoteRequestClient
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);

        return $this->quoteRequestsRestResponseBuilder->createQuoteRequestCollectionRestResponse($quoteRequestCollectionTransfer);
    }
}
