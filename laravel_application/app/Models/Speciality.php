<?php
/*
 * File name: Speciality.php
 * Last modified: 2021.04.18 at 11:48:14
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */

namespace App\Models;

use App\Traits\HasTranslations;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class Speciality
 * @package App\Models
 * @version January 19, 2021, 2:04 pm UTC
 *
 * @property Speciality parentSpeciality
 * @property Speciality[] subSpecialities
 * @property Doctor[] featuredDoctors
 * @property Doctor[] doctors
 * @property string name
 * @property string color
 * @property string description
 * @property integer order
 * @property boolean featured
 * @property boolean is_parent
 * @property integer parent_id
 */
class Speciality extends Model implements HasMedia
{
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }
    use HasTranslations;
    use HasFactory;

    /**
     * Validation rules
     *
     * @var array
     */
    public static array $rules = [
        'name' => 'required|max:127',
        'color' => 'required|max:36',
        'description' => 'nullable',
        'order' => 'nullable|numeric|min:0',
        'parent_id' => 'nullable|exists:specialities,id'
    ];
    public array $translatable = [
        'name',
        'description'
    ];
    public $table = 'specialities';
    public $fillable = [
        'name',
        'color',
        'description',
        'featured',
        'order',
        'parent_id'
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'color' => 'string',
        'description' => 'string',
        'image' => 'string',
        'featured' => 'boolean',
        'order' => 'integer',
        'parent_id' => 'integer'
    ];
    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'has_media'
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
    ];

    /**
     * @param Media|null $media
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->sharpen(10);

        $this->addMediaConversion('icon')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->sharpen(10);
    }

    /**
     * to generate media url in case of fallback will
     * return the file type icon
     * @param string $conversion
     * @return string url
     */
    public function getFirstMediaUrl(string $collectionName = 'default', string $conversion = ''): string
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        $array = explode('.', $url);
        $extension = strtolower(end($array));
        if (in_array($extension, config('media-library.extensions_has_thumb'))) {
            return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
        } else {
            return asset(config('media-library.icons_folder') . '/' . $extension . '.png');
        }
    }

    public function getCustomFieldsAttribute(): array
    {
        $hasCustomField = in_array(static::class, setting('custom_field_models', []));
        if (!$hasCustomField) {
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields', 'custom_fields.id', '=', 'custom_field_values.custom_field_id')
            ->where('custom_fields.in_table', '=', true)
            ->get()->toArray();

        return convertToAssoc($array, 'name');
    }

     public function customFieldsValues(): MorphMany
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    /**
     * Add Media to api results
     * @return bool
     */
    public function getHasMediaAttribute(): bool
    {
        return $this->hasMedia('image') ? true : false;
    }

    /**
     * @return BelongsTo
     **/
    public function parentSpeciality(): BelongsTo
    {
        return $this->belongsTo(Speciality::class, 'parent_id', 'id');
    }

    /**
     * @return HasMany
     **/
    public function subSpecialities(): HasMany
    {
        return $this->hasMany(Speciality::class, 'parent_id')->orderBy('order');
    }

    /**
     * @return BelongsToMany
     **/
    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class, 'doctor_specialities');
    }

    /**
     * @return BelongsToMany
     **/
    public function featuredDoctors(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class, 'doctor_specialities')->where('doctors.featured', '=', true);
    }

    public function discountables(): MorphMany
    {
        return $this->morphMany('App\Models\Discountable', 'discountable');
    }
}
