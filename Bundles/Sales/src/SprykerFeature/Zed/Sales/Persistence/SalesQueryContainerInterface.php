<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Persistence;

use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesExpenseQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddressQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;

interface SalesQueryContainerInterface
{

    /**
     * @param int $idSalesOrder
     *
     * @return SpySalesOrderQuery
     */
    public function querySalesOrderById($idSalesOrder);

    /**
     * @return SpySalesOrderQuery
     */
    public function querySalesOrder();

    /**
     * @var int
     *
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsByIdSalesOrder($idOrder);

    /**
     * @var int
     *
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItemsWithState($idOrder);

    /**
     * @return SpySalesOrderItemQuery
     */
    public function querySalesOrderItem();

    /**
     * @param int $orderId
     *
     * @return SpySalesExpenseQuery
     */
    public function querySalesExpensesByOrderId($orderId);

    /**
     * @param int $idSalesOrderAddress
     *
     * @return SpySalesOrderAddressQuery
     */
    public function querySalesOrderAddressById($idSalesOrderAddress);

}
