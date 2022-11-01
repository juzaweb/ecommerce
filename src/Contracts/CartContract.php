<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/juzacms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Ecommerce\Contracts;

use Illuminate\Support\Collection;
use Juzaweb\Ecommerce\Models\Cart;
use Juzaweb\Ecommerce\Models\ProductVariant;

interface CartContract
{
    public function make(string|Cart $cart): static;

    public function add(int|ProductVariant $variant, int $quantity): bool;

    public function update(int|ProductVariant $variant, int $quantity): bool;

    public function addOrUpdate(int|ProductVariant $variant, int $quantity) : bool;

    public function bulkUpdate(array $items) : bool;

    public function removeItem(int $variantId) : bool;

    public function remove() : bool;

    public function getItems() : array;

    public function isEmpty(): bool;

    public function isNotEmpty(): bool;

    public function totalItems() : int;

    public function totalPrice() : float;

    public function getCollectionItems(): Collection;

    public function getCode(): string;

    public function toArray(): array;
}
