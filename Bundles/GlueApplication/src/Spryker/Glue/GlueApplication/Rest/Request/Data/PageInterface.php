<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

interface PageInterface
{
    /**
     * @return int
     */
    public function getOffset(): int;

    /**
     * @return int
     */
    public function getLimit(): int;
}
