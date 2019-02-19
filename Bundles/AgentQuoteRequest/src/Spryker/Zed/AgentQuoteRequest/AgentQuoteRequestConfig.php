<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class AgentQuoteRequestConfig extends AbstractBundleConfig
{
    /**
     * @see \Spryker\Shared\QuoteRequest\QuoteRequestConfig::STATUS_WAITING
     */
    public const STATUS_WAITING = 'waiting';

    /**
     * @see \Spryker\Shared\QuoteRequest\QuoteRequestConfig::STATUS_CANCELED
     */
    public const STATUS_CANCELED = 'canceled';
}
