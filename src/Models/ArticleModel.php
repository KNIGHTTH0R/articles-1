<?php

namespace InetStudio\Articles\Models;

use Spatie\Tags\HasTags;
use Cocur\Slugify\Slugify;
use Phoenix\EloquentMeta\MetaTrait;
use InetStudio\Tags\Models\TagModel;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use InetStudio\Products\Traits\HasProducts;
use InetStudio\Statuses\Models\StatusModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\Categories\Traits\HasCategories;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Venturecraft\Revisionable\RevisionableTrait;
use InetStudio\Ingredients\Traits\HasIngredients;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;

/**
 * InetStudio\Articles\Models\ArticleModel
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $content
 * @property string|null $publish_date
 * @property string $webmaster_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property \Kalnoy\Nestedset\Collection|\InetStudio\Categories\Models\CategoryModel[] $categories
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\Phoenix\EloquentMeta\Meta[] $meta
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property \Illuminate\Database\Eloquent\Collection|\InetStudio\Tags\Models\TagModel[] $tags
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel findSimilarSlugs(\Illuminate\Database\Eloquent\Model $model, $attribute, $config, $slug)
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Articles\Models\ArticleModel onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel wherePublishDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel whereWebmasterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel withAllCategories($categories, $column = 'slug')
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel withAllTags($tags, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel withAnyCategories($categories, $column = 'slug')
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel withAnyTags($tags, $type = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel withCategories($categories, $column = 'slug')
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Articles\Models\ArticleModel withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel withoutAnyCategories()
 * @method static \Illuminate\Database\Eloquent\Builder|\InetStudio\Articles\Models\ArticleModel withoutCategories($categories, $column = 'slug')
 * @method static \Illuminate\Database\Query\Builder|\InetStudio\Articles\Models\ArticleModel withoutTrashed()
 * @mixin \Eloquent
 */
class ArticleModel extends Model implements HasMediaConversions
{
    use HasTags;
    use MetaTrait;
    use Sluggable;
    use HasProducts;
    use SoftDeletes;
    use HasCategories;
    use HasMediaTrait;
    use HasIngredients;
    use RevisionableTrait;
    use SluggableScopeHelpers;

    const HREF = '/article/';

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'description', 'content',
        'publish_date', 'webmaster_id', 'status_id',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function status()
    {
        return $this->hasOne(StatusModel::class, 'id', 'status_id');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title',
                'unique' => true,
            ],
        ];
    }

    protected $revisionCreationsEnabled = true;

    /**
     * Правила для транслита.
     *
     * @param Slugify $engine
     * @return Slugify
     */
    public function customizeSlugEngine(Slugify $engine)
    {
        $rules = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'jo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p',
            'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'shh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'je', 'ю' => 'ju', 'я' => 'ja',
        ];

        $engine->addRules($rules);

        return $engine;
    }

    /**
     * Ссылка на материал.
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getHrefAttribute()
    {
        return url(self::HREF . (!empty($this->slug) ? $this->slug : $this->id));
    }

    public static function getTagClassName(): string
    {
        return TagModel::class;
    }

    public function registerMediaConversions()
    {
        $quality = (config('articles.images.quality')) ? config('articles.images.quality') : 75;

        if (config('articles.images.conversions')) {
            foreach (config('articles.images.conversions') as $collection => $image) {
                foreach ($image as $crop) {
                    foreach ($crop as $conversion) {
                        $this->addMediaConversion($conversion['name'])
                            ->quality($quality)
                            ->width($conversion['size']['width'])
                            ->height($conversion['size']['height'])
                            ->performOnCollections($collection);
                    }
                }
            }
        }
    }
}
