<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Dependency\Service;

class CmsBlockCategoryStorageToUtilSanitizeServiceBridge implements CmsBlockCategoryStorageToUtilSanitizeServiceInterface
{
    /**
     * @var \Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @param \Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface $utilSanitizeService
     */
    public function __construct($utilSanitizeService)
    {
        $this->utilSanitizeService = $utilSanitizeService;
    }

    /**
     * @deprecated Use filterOutEmptyValuesRecursively() instead.
     *
     * @param array $array
     *
     * @return array
     */
    public function arrayFilterRecursive(array $array): array
    {
        return $this->utilSanitizeService->arrayFilterRecursive($array);
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public function filterOutEmptyValuesRecursively(array $array): array
    {
        return $this->utilSanitizeService->filterOutEmptyValuesRecursively($array);
    }
}
