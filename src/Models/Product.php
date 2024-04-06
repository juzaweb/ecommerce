<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Ecommerce\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Juzaweb\Backend\Models\Comment;
use Juzaweb\Backend\Models\MenuItem;
use Juzaweb\Backend\Models\Post;
use Juzaweb\Backend\Models\PostLike;
use Juzaweb\Backend\Models\PostMeta;
use Juzaweb\Backend\Models\PostRating;
use Juzaweb\Backend\Models\PostView;
use Juzaweb\Backend\Models\Resource;
use Juzaweb\Backend\Models\Taxonomy;
use Juzaweb\CMS\Database\Factories\PostFactory;
use Juzaweb\CMS\Models\User;

/**
 * Juzaweb\Ecommerce\Models\Product
 *
 * @property int $id
 * @property string $title
 * @property string|null $thumbnail
 * @property string $slug
 * @property string|null $description
 * @property string|null $content
 * @property string $status
 * @property int $views
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string $type
 * @property array|null $json_metas
 * @property array|null $json_taxonomies
 * @property int|null $site_id
 * @property float $rating
 * @property int $total_rating
 * @property int $total_comment
 * @property string|null $uuid
 * @property string $locale
 * @property-read Collection<int, Taxonomy> $categories
 * @property-read int|null $categories_count
 * @property-read Collection<int, Comment> $comments
 * @property-read int|null $comments_count
 * @property-read User|null $createdBy
 * @property-read Collection<int, DownloadLink> $downloadLinks
 * @property-read int|null $download_links_count
 * @property-read Collection<int, PostLike> $likes
 * @property-read int|null $likes_count
 * @property-read Collection<int, MenuItem> $menuItems
 * @property-read int|null $menu_items_count
 * @property-read Collection<int, PostMeta> $metas
 * @property-read int|null $metas_count
 * @property-read Collection<int, OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read Collection<int, Order> $orders
 * @property-read int|null $orders_count
 * @property-read Collection<int, PostView> $postViews
 * @property-read int|null $post_views_count
 * @property-read Collection<int, PostRating> $ratings
 * @property-read int|null $ratings_count
 * @property-read Collection<int, Resource> $resources
 * @property-read int|null $resources_count
 * @property-read Collection<int, Taxonomy> $tags
 * @property-read int|null $tags_count
 * @property-read Collection<int, Taxonomy> $taxonomies
 * @property-read int|null $taxonomies_count
 * @property-read User|null $updatedBy
 * @method static PostFactory factory($count = null, $state = [])
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereContent($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereCreatedBy($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Post whereFilter(array $params = [])
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereJsonMetas($value)
 * @method static Builder|Product whereJsonTaxonomies($value)
 * @method static Builder|Product whereLocale($value)
 * @method static Builder|Post whereMeta(string $key, array|string|int|null $value)
 * @method static Builder|Post whereMetaIn(string $key, array $values)
 * @method static Builder|Post wherePublish()
 * @method static Builder|Product whereRating($value)
 * @method static Builder|Post whereSearch(array $params)
 * @method static Builder|Product whereSiteId($value)
 * @method static Builder|Product whereSlug($value)
 * @method static Builder|Product whereStatus($value)
 * @method static Builder|Post whereTaxonomy(int $taxonomy)
 * @method static Builder|Post whereTaxonomyIn(array $taxonomies)
 * @method static Builder|Product whereThumbnail($value)
 * @method static Builder|Product whereTitle($value)
 * @method static Builder|Product whereTotalComment($value)
 * @method static Builder|Product whereTotalRating($value)
 * @method static Builder|Product whereType($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @method static Builder|Product whereUpdatedBy($value)
 * @method static Builder|Product whereUuid($value)
 * @method static Builder|Product whereViews($value)
 * @mixin Eloquent
 */
class Product extends Post
{
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'post_id', 'id');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(
            Order::class,
            OrderItem::getTableName(),
            'product_id',
            'order_id'
        );
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(
            OrderItem::class,
            'product_id',
            'id'
        );
    }

    public function downloadLinks(): HasMany
    {
        return $this->hasMany(DownloadLink::class, 'product_id', 'id');
    }
}
