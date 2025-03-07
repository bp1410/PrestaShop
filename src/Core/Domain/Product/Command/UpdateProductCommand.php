<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
declare(strict_types=1);

namespace PrestaShop\PrestaShop\Core\Domain\Product\Command;

use PrestaShop\Decimal\DecimalNumber;
use PrestaShop\PrestaShop\Adapter\Product\CommandHandler\UpdateProductHandler;
use PrestaShop\PrestaShop\Core\Domain\Manufacturer\Exception\ManufacturerConstraintException;
use PrestaShop\PrestaShop\Core\Domain\Manufacturer\ValueObject\ManufacturerId;
use PrestaShop\PrestaShop\Core\Domain\Manufacturer\ValueObject\ManufacturerIdInterface;
use PrestaShop\PrestaShop\Core\Domain\Manufacturer\ValueObject\NoManufacturerId;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\Ean13;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\Isbn;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductCondition;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductVisibility;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\RedirectOption;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\Reference;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\Upc;
use PrestaShop\PrestaShop\Core\Domain\Shop\ValueObject\ShopConstraint;

/**
 * Contains all the data needed to handle the product update.
 *
 * @see UpdateProductHandler
 *
 * This command is only designed for the general data of product which can be persisted in one call.
 * It was not designed to handle the product relations.
 */
class UpdateProductCommand
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var ShopConstraint
     */
    private $shopConstraint;

    /**
     * @var string[]|null
     */
    private $localizedNames;

    /**
     * @var string[]|null key value pairs where key is the id of language
     */
    private $localizedDescriptions;

    /**
     * @var string[]|null key value pairs where key is the id of language
     */
    private $localizedShortDescriptions;

    /**
     * @var ProductVisibility|null
     */
    private $visibility;

    /**
     * @var bool|null
     */
    private $availableForOrder;

    /**
     * @var bool|null
     */
    private $onlineOnly;

    /**
     * @var bool|null
     */
    private $showPrice;

    /**
     * @var ProductCondition|null
     */
    private $condition;

    /**
     * @var bool|null
     */
    private $showCondition;

    /**
     * @var ManufacturerIdInterface|null
     */
    private $manufacturerId;

    /**
     * @var DecimalNumber|null
     */
    private $price;

    /**
     * @var DecimalNumber|null
     */
    private $ecotax;

    /**
     * @var int|null
     */
    private $taxRulesGroupId;

    /**
     * @var bool|null
     */
    private $onSale;

    /**
     * @var DecimalNumber|null
     */
    private $wholesalePrice;

    /**
     * @var DecimalNumber|null
     */
    private $unitPrice;

    /**
     * @var string|null
     */
    private $unity;

    /**
     * @var string[]|null
     */
    private $localizedMetaTitles;

    /**
     * @var string[]|null
     */
    private $localizedMetaDescriptions;

    /**
     * @var string[]|null
     */
    private $localizedLinkRewrites;

    /**
     * @var RedirectOption|null
     */
    private $redirectOption;

    /**
     * @var Isbn|null
     */
    private $isbn;

    /**
     * @var Upc|null
     */
    private $upc;

    /**
     * @var Ean13|null
     */
    private $ean13;

    /**
     * @var string|null
     */
    private $mpn;

    /**
     * @var Reference|null
     */
    private $reference;

    /**
     * @param int $productId
     * @param ShopConstraint $shopConstraint
     */
    public function __construct(int $productId, ShopConstraint $shopConstraint)
    {
        $this->productId = new ProductId($productId);
        $this->shopConstraint = $shopConstraint;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return ShopConstraint
     */
    public function getShopConstraint(): ShopConstraint
    {
        return $this->shopConstraint;
    }

    /**
     * @return string[]|null
     */
    public function getLocalizedMetaTitles(): ?array
    {
        return $this->localizedMetaTitles;
    }

    /**
     * @param string[] $localizedMetaTitles key => value pairs where each key represents language id
     *
     * @return self
     */
    public function setLocalizedMetaTitles(array $localizedMetaTitles): self
    {
        $this->localizedMetaTitles = $localizedMetaTitles;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getLocalizedMetaDescriptions(): ?array
    {
        return $this->localizedMetaDescriptions;
    }

    /**
     * @param string[] $localizedMetaDescriptions key => value pairs where each key represents language id
     *
     * @return self
     */
    public function setLocalizedMetaDescriptions(array $localizedMetaDescriptions): self
    {
        $this->localizedMetaDescriptions = $localizedMetaDescriptions;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getLocalizedLinkRewrites(): ?array
    {
        return $this->localizedLinkRewrites;
    }

    /**
     * @param string[] $localizedLinkRewrites key => value pairs where each key represents language id
     *
     * @return self
     */
    public function setLocalizedLinkRewrites(array $localizedLinkRewrites): self
    {
        $this->localizedLinkRewrites = $localizedLinkRewrites;

        return $this;
    }

    /**
     * @return RedirectOption|null
     */
    public function getRedirectOption(): ?RedirectOption
    {
        return $this->redirectOption;
    }

    /**
     * @param string $redirectType
     * @param int $redirectTarget
     *
     * @return self
     */
    public function setRedirectOption(string $redirectType, int $redirectTarget): self
    {
        $this->redirectOption = new RedirectOption($redirectType, $redirectTarget);

        return $this;
    }

    /**
     * @return DecimalNumber|null
     */
    public function getPrice(): ?DecimalNumber
    {
        return $this->price;
    }

    /**
     * @param string $price
     *
     * @return self
     */
    public function setPrice(string $price): self
    {
        $this->price = new DecimalNumber($price);

        return $this;
    }

    /**
     * @return DecimalNumber|null
     */
    public function getEcotax(): ?DecimalNumber
    {
        return $this->ecotax;
    }

    /**
     * @param string $ecotax
     *
     * @return self
     */
    public function setEcotax(string $ecotax): self
    {
        $this->ecotax = new DecimalNumber($ecotax);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTaxRulesGroupId(): ?int
    {
        return $this->taxRulesGroupId;
    }

    /**
     * @param int $taxRulesGroupId
     *
     * @return self
     */
    public function setTaxRulesGroupId(int $taxRulesGroupId): self
    {
        $this->taxRulesGroupId = $taxRulesGroupId;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isOnSale(): ?bool
    {
        return $this->onSale;
    }

    /**
     * @param bool $onSale
     *
     * @return self
     */
    public function setOnSale(bool $onSale): self
    {
        $this->onSale = $onSale;

        return $this;
    }

    /**
     * @return DecimalNumber|null
     */
    public function getWholesalePrice(): ?DecimalNumber
    {
        return $this->wholesalePrice;
    }

    /**
     * @param string $wholesalePrice
     *
     * @return self
     */
    public function setWholesalePrice(string $wholesalePrice): self
    {
        $this->wholesalePrice = new DecimalNumber($wholesalePrice);

        return $this;
    }

    /**
     * @return DecimalNumber|null
     */
    public function getUnitPrice(): ?DecimalNumber
    {
        return $this->unitPrice;
    }

    /**
     * @param string $unitPrice
     *
     * @return self
     */
    public function setUnitPrice(string $unitPrice): self
    {
        $this->unitPrice = new DecimalNumber($unitPrice);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUnity(): ?string
    {
        return $this->unity;
    }

    /**
     * @param string $unity
     *
     * @return self
     */
    public function setUnity(string $unity): self
    {
        $this->unity = $unity;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getLocalizedNames(): ?array
    {
        return $this->localizedNames;
    }

    /**
     * @param string[] $localizedNames
     *
     * @return self
     */
    public function setLocalizedNames(array $localizedNames): self
    {
        $this->localizedNames = $localizedNames;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getLocalizedDescriptions(): ?array
    {
        return $this->localizedDescriptions;
    }

    /**
     * @param string[] $localizedDescriptions
     *
     * @return self
     */
    public function setLocalizedDescriptions(array $localizedDescriptions): self
    {
        $this->localizedDescriptions = $localizedDescriptions;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getLocalizedShortDescriptions(): ?array
    {
        return $this->localizedShortDescriptions;
    }

    /**
     * @param string[] $localizedShortDescriptions
     *
     * @return self
     */
    public function setLocalizedShortDescriptions(array $localizedShortDescriptions): self
    {
        $this->localizedShortDescriptions = $localizedShortDescriptions;

        return $this;
    }

    /**
     * @return ProductVisibility|null
     */
    public function getVisibility(): ?ProductVisibility
    {
        return $this->visibility;
    }

    /**
     * @return bool|null
     */
    public function isAvailableForOrder(): ?bool
    {
        return $this->availableForOrder;
    }

    /**
     * @param string $visibility
     *
     * @return self
     */
    public function setVisibility(string $visibility): self
    {
        $this->visibility = new ProductVisibility($visibility);

        return $this;
    }

    /**
     * @param bool $availableForOrder
     *
     * @return self
     */
    public function setAvailableForOrder(bool $availableForOrder): self
    {
        $this->availableForOrder = $availableForOrder;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isOnlineOnly(): ?bool
    {
        return $this->onlineOnly;
    }

    /**
     * @param bool $onlineOnly
     *
     * @return self
     */
    public function setOnlineOnly(bool $onlineOnly): self
    {
        $this->onlineOnly = $onlineOnly;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function showPrice(): ?bool
    {
        return $this->showPrice;
    }

    /**
     * @param bool $showPrice
     *
     * @return self
     */
    public function setShowPrice(bool $showPrice): self
    {
        $this->showPrice = $showPrice;

        return $this;
    }

    /**
     * @return ProductCondition|null
     */
    public function getCondition(): ?ProductCondition
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     *
     * @return self
     */
    public function setCondition(string $condition): self
    {
        $this->condition = new ProductCondition($condition);

        return $this;
    }

    /**
     * @param bool $showCondition
     *
     * @return self
     */
    public function setShowCondition(bool $showCondition): self
    {
        $this->showCondition = $showCondition;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function showCondition(): ?bool
    {
        return $this->showCondition;
    }

    /**
     * @return ManufacturerIdInterface|null
     */
    public function getManufacturerId(): ?ManufacturerIdInterface
    {
        return $this->manufacturerId;
    }

    /**
     * @param int $manufacturerId
     *
     * @throws ManufacturerConstraintException
     *
     * @return self
     */
    public function setManufacturerId(int $manufacturerId): self
    {
        $this->manufacturerId = NoManufacturerId::NO_MANUFACTURER_ID === $manufacturerId ?
            new NoManufacturerId() :
            new ManufacturerId($manufacturerId)
        ;

        return $this;
    }

    /**
     * @return Isbn|null
     */
    public function getIsbn(): ?Isbn
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     *
     * @return self
     */
    public function setIsbn(string $isbn): self
    {
        $this->isbn = new Isbn($isbn);

        return $this;
    }

    /**
     * @return Upc|null
     */
    public function getUpc(): ?Upc
    {
        return $this->upc;
    }

    /**
     * @param string $upc
     *
     * @return self
     */
    public function setUpc(string $upc): self
    {
        $this->upc = new Upc($upc);

        return $this;
    }

    /**
     * @return Ean13|null
     */
    public function getEan13(): ?Ean13
    {
        return $this->ean13;
    }

    /**
     * @param string $ean13
     *
     * @return self
     */
    public function setEan13(string $ean13): self
    {
        $this->ean13 = new Ean13($ean13);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMpn(): ?string
    {
        return $this->mpn;
    }

    /**
     * @param string $mpn
     *
     * @return self
     */
    public function setMpn(string $mpn): self
    {
        $this->mpn = $mpn;

        return $this;
    }

    /**
     * @return Reference|null
     */
    public function getReference(): ?Reference
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     *
     * @return self
     */
    public function setReference(string $reference): self
    {
        $this->reference = new Reference($reference);

        return $this;
    }
}
