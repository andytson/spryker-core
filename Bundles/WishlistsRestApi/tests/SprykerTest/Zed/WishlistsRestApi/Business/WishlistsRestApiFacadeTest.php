<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\WishlistsRestApi\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\WishlistFilterTransfer;
use Generated\Shared\Transfer\WishlistItemRequestTransfer;
use Generated\Shared\Transfer\WishlistRequestTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Shared\WishlistsRestApi\WishlistsRestApiConfig;
use Spryker\Zed\Wishlist\Business\Exception\MissingWishlistException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group WishlistsRestApi
 * @group Business
 * @group Facade
 * @group WishlistsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class WishlistsRestApiFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\WishlistsRestApi\WishlistsRestApiBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = $this->tester->haveCustomer();
    }

    /**
     * @return void
     */
    public function testUpdateWishlistWillUpdateWishlistsName(): void
    {
        //Arrange
        $newName = 'New';
        $originalName = 'Original';
        $originalWishlist = $this->tester->haveWishlist(
            [
                'name' => $originalName,
                'fkCustomer' => $this->customer->getIdCustomer(),
            ]
        );
        $wishlistRequestTransfer = (new WishlistRequestTransfer())
            ->setUuid($originalWishlist->getUuid())
            ->setIdCustomer($this->customer->getIdCustomer())
            ->setWishlist((new WishlistTransfer())->setName($newName));

        //Act
        $wishlistResponseTransfer = $this->tester->getWishlistsRestApiFacade()->updateWishlist($wishlistRequestTransfer);

        //Assert
        $wishlistTransfer = $this->tester->getWishlistByName($this->customer->getIdCustomer(), $newName);

        $actualWishlistTransfer = $wishlistResponseTransfer->getWishlist();
        $this->assertTrue($wishlistResponseTransfer->getIsSuccess());
        $this->assertNotNull($actualWishlistTransfer);
        $this->assertSame($actualWishlistTransfer->getIdWishlist(), $wishlistTransfer->getIdWishlist());
        $this->assertSame($actualWishlistTransfer->getName(), $wishlistTransfer->getName());
        $this->assertSame($actualWishlistTransfer->getName(), $newName);
    }

    /**
     * @return void
     */
    public function testUpdateNonExistingWishlistShouldReturnError(): void
    {
        //Arrange
        $wishlistRequestTransfer = (new WishlistRequestTransfer())
            ->setUuid('uuid-does-not-exist')
            ->setIdCustomer($this->customer->getIdCustomer());

        //Act
        $wishlistResponseTransfer = $this->tester->getWishlistsRestApiFacade()->updateWishlist($wishlistRequestTransfer);

        //Assert
        $this->assertFalse($wishlistResponseTransfer->getIsSuccess());
        $this->assertSame(
            $wishlistResponseTransfer->getErrorIdentifier(),
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND
        );
    }

    /**
     * @return void
     */
    public function testUpdateWishlistWithWrongNameShouldReturnError(): void
    {
        //Arrange
        $originalName = 'Original';
        $wrongName = '{{New';
        $wishlist = $this->tester->haveWishlist(
            [
                'name' => $originalName,
                'fkCustomer' => $this->customer->getIdCustomer(),
            ]
        );
        $wishlistRequestTransfer = (new WishlistRequestTransfer())
            ->setUuid($wishlist->getUuid())
            ->setIdCustomer($this->customer->getIdCustomer())
            ->setWishlist((new WishlistTransfer())->setName($wrongName));

        //Act
        $wishlistResponseTransfer = $this->tester->getWishlistsRestApiFacade()->updateWishlist($wishlistRequestTransfer);

        //Assert
        $this->assertFalse($wishlistResponseTransfer->getIsSuccess());
        $this->assertSame(
            $wishlistResponseTransfer->getErrorIdentifier(),
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NAME_WRONG_FORMAT
        );
    }

    /**
     * @return void
     */
    public function testUpdateWishlistWithDuplicateNameShouldReturnError(): void
    {
        //Arrange
        $firstWishlistName = 'First';
        $secondWishlistName = 'Second';
        $wishlist1 = $this->tester->haveWishlist(
            [
                'name' => $firstWishlistName,
                'fkCustomer' => $this->customer->getIdCustomer(),
            ]
        );
        $this->tester->haveWishlist(
            [
                'name' => $secondWishlistName,
                'fkCustomer' => $this->customer->getIdCustomer(),
            ]
        );
        $wishlistRequestTransfer = (new WishlistRequestTransfer())
            ->setUuid($wishlist1->getUuid())
            ->setIdCustomer($this->customer->getIdCustomer())
            ->setWishlist((new WishlistTransfer())->setName($secondWishlistName));

        //Act
        $wishlistResponseTransfer = $this->tester->getWishlistsRestApiFacade()->updateWishlist($wishlistRequestTransfer);

        //Assert
        $this->assertFalse($wishlistResponseTransfer->getIsSuccess());
        $this->assertSame(
            $wishlistResponseTransfer->getErrorIdentifier(),
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NAME_ALREADY_EXIST
        );
    }

    /**
     * @return void
     */
    public function testDeleteWishlistWillRemoveWishlist(): void
    {
        //Arrange
        $wishlistName = 'name';
        $wishlist = $this->tester->haveWishlist(
            [
                'fkCustomer' => $this->customer->getIdCustomer(),
                'name' => $wishlistName,
            ]
        );
        $wishlistFilterTransfer = (new WishlistFilterTransfer())
            ->setUuid($wishlist->getUuid())
            ->setIdCustomer($this->customer->getIdCustomer());

        //Act
        $this->tester->getWishlistsRestApiFacade()->deleteWishlist($wishlistFilterTransfer);

        //Assert
        $this->expectException(MissingWishlistException::class);
        $this->tester->getWishlistByName($this->customer->getIdCustomer(), $wishlistName);
    }

    /**
     * @return void
     */
    public function testDeleteNonExistingWishlistShouldReturnError(): void
    {
        //Arrange
        $wishlistRequestTransfer = (new WishlistFilterTransfer())
            ->setUuid('uuid-does-not-exist')
            ->setIdCustomer($this->customer->getIdCustomer());

        //Act
        $wishlistResponseTransfer = $this->tester->getWishlistsRestApiFacade()->deleteWishlist($wishlistRequestTransfer);

        //Assert
        $this->assertFalse($wishlistResponseTransfer->getIsSuccess());
        $this->assertSame(
            $wishlistResponseTransfer->getErrorIdentifier(),
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND
        );
    }

    /**
     * @return void
     */
    public function testAddWishlistItemShouldAddItem(): void
    {
        //Arrange
        $wishlistName = 'name';
        $wishlist = $this->tester->haveWishlist(
            [
                'name' => $wishlistName,
                'fkCustomer' => $this->customer->getIdCustomer(),
            ]
        );
        $concreteProduct = $this->tester->haveProduct();
        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuidWishlist($wishlist->getUuid())
            ->setIdCustomer($this->customer->getIdCustomer())
            ->setSku($concreteProduct->getSku());

        //Act
        $wishlistItemResponseTransfer = $this->tester->getWishlistsRestApiFacade()->addWishlistItem($wishlistItemRequestTransfer);

        //Assert
        $wishlistTransfer = $this->tester->getWishlistByName($this->customer->getIdCustomer(), $wishlistName);

        $this->assertTrue($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertEmpty($wishlistItemResponseTransfer->getErrors());
        $this->assertNull($wishlistItemResponseTransfer->getErrorIdentifier());
        $this->assertNotNull($wishlistTransfer->getIdWishlist());
        $this->assertSame(1, $wishlistTransfer->getNumberOfItems());
        $this->assertSame(
            $concreteProduct->getSku(),
            $wishlistItemResponseTransfer->getWishlistItem()->getSku()
        );
    }

    /**
     * @return void
     */
    public function testAddWishlistItemToNonExistingWishlistShouldReturnError(): void
    {
        //Arrange
        $concreteProduct = $this->tester->haveProduct();
        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuidWishlist('uuid-does-not-exist')
            ->setIdCustomer($this->customer->getIdCustomer())
            ->setSku($concreteProduct->getSku());

        //Act
        $wishlistItemResponseTransfer = $this->tester->getWishlistsRestApiFacade()->addWishlistItem($wishlistItemRequestTransfer);

        //Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertSame(
            $wishlistItemResponseTransfer->getErrorIdentifier(),
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND
        );
    }

    /**
     * @return void
     */
    public function testAddNonExistingProductToWishlistShouldReturnError(): void
    {
        //Arrange
        $wishlist = $this->tester->haveWishlist(
            [
                'name' => 'name',
                'fkCustomer' => $this->customer->getIdCustomer(),
            ]
        );
        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuidWishlist($wishlist->getUuid())
            ->setIdCustomer($this->customer->getIdCustomer())
            ->setSku('non-existing-sku');

        //Act
        $wishlistItemResponseTransfer = $this->tester->getWishlistsRestApiFacade()->addWishlistItem($wishlistItemRequestTransfer);

        //Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertSame(
            $wishlistItemResponseTransfer->getErrorIdentifier(),
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_ITEM_CANT_BE_ADDED
        );
    }

    /**
     * @return void
     */
    public function testDeleteWishlistItemShouldDeleteItem(): void
    {
        //Arrange
        $wishlistName = 'name';
        $wishlist = $this->tester->haveWishlist(
            [
                'fkCustomer' => $this->customer->getIdCustomer(),
                'name' => $wishlistName,
            ]
        );
        $concreteProduct = $this->tester->haveProduct();
        $wishlistItem = $this->tester->haveItemInWishlist(
            [
                'fkWishlist' => $wishlist->getIdWishlist(),
                'fkCustomer' => $this->customer->getIdCustomer(),
                'sku' => $concreteProduct->getSku(),
                'wishlistName' => $wishlist->getName(),
            ]
        );
        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuidWishlist($wishlist->getUuid())
            ->setIdCustomer($this->customer->getIdCustomer())
            ->setSku($wishlistItem->getSku());

        //Act
        $wishlistItemResponseTransfer = $this->tester->getWishlistsRestApiFacade()->deleteWishlistItem($wishlistItemRequestTransfer);

        //Assert
        $wishlistTransfer = $this->tester->getWishlistByName($this->customer->getIdCustomer(), $wishlistName);

        $this->assertTrue($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertNotNull($wishlistTransfer->getIdWishlist());
        $this->assertCount(0, $wishlistTransfer->getWishlistItems());
    }

    /**
     * @return void
     */
    public function testDeleteWishlistItemInNonExistingWishlistShouldReturnError(): void
    {
        //Arrange
        $wishlist = $this->tester->haveWishlist(
            [
                'fkCustomer' => $this->customer->getIdCustomer(),
                'name' => 'name',
            ]
        );
        $concreteProduct = $this->tester->haveProduct();
        $wishlistItem = $this->tester->haveItemInWishlist(
            [
                'fkWishlist' => $wishlist->getIdWishlist(),
                'fkCustomer' => $this->customer->getIdCustomer(),
                'sku' => $concreteProduct->getSku(),
                'wishlistName' => $wishlist->getName(),
            ]
        );
        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuidWishlist('uuid-does-not-exist')
            ->setIdCustomer($this->customer->getIdCustomer())
            ->setSku($wishlistItem->getSku());

        //Act
        $wishlistItemResponseTransfer = $this->tester->getWishlistsRestApiFacade()->deleteWishlistItem($wishlistItemRequestTransfer);

        //Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertSame(
            WishlistsRestApiConfig::ERROR_IDENTIFIER_WISHLIST_NOT_FOUND,
            $wishlistItemResponseTransfer->getErrorIdentifier()
        );
    }

    /**
     * @return void
     */
    public function testDeleteNonExistingWishlistItemFromWishlistShouldReturnError(): void
    {
        //Arrange
        $wishlist = $this->tester->haveWishlist(
            [
                'fkCustomer' => $this->customer->getIdCustomer(),
                'name' => 'name',
            ]
        );
        $concreteProduct = $this->tester->haveProduct();

        $wishlistItemRequestTransfer = (new WishlistItemRequestTransfer())
            ->setUuidWishlist($wishlist->getUuid())
            ->setIdCustomer($this->customer->getIdCustomer())
            ->setSku($concreteProduct->getSku());

        //Act
        $wishlistItemResponseTransfer = $this->tester->getWishlistsRestApiFacade()->deleteWishlistItem($wishlistItemRequestTransfer);

        //Assert
        $this->assertFalse($wishlistItemResponseTransfer->getIsSuccess());
        $this->assertSame(
            WishlistsRestApiConfig::ERROR_IDENTIFIER_ITEM_WITH_SKU_NOT_FOUND_IN_WISHLIST,
            $wishlistItemResponseTransfer->getErrorIdentifier()
        );
    }
}
