<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    const NAME = 'products';

    use Traits\UuidTrait;

    protected $table = 'prices';
    public $timestamps = true;

    protected $fillable = [
        'description',
        'label',
        'image',
        'open_graph_image',
        'subtitle',
        'weight',
        'active',
        'handling_fee',
        'list_price',
        'sale_price',
    ];

    protected $hidden = [
        'list_price',
        'expiry',
    ];

    protected $appends = [
        'brand_name',
        'image_url',
        'claim_image',
        'claim_image_url',
        'imessage_image',
        'imessage_image_url',
        'open_graph_image_url',
    ];

    public function getBrandNameAttribute()
    {
        if ($this->brand()->first()) {
            $name = $this->brand()->first()->name;
            return $name;
        }
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class)->withTrashed();
    }

    public function soldItems()
    {
        return $this->hasMany(Hug::class, 'purchase_id');
    }

    public function getHandlingFeeAsString() : string
    {
        return number_format($this->handling_fee / 100, 2);
    }

    public function getImageUrlAttribute()
    {
        return $this->image
            ? (env('APP_IMAGE_URL') . '/offers/' . $this->image)
            : null;
    }

    /**
     * Backwards support for price.claim_image
     */
    public function getClaimImageAttribute()
    {
        return $this->image;
    }

    /**
     * Backwards support for price.imessage_image
     */
    public function getIMessageImageAttribute()
    {
        return $this->image;
    }

    /**
     * Backwards support for price.claim_image_url
     */
    public function getClaimImageUrlAttribute()
    {
        return $this->getImageUrlAttribute();
    }

    /**
     * Backwards support for price.imessage_image_url
     */
    public function getIMessageImageUrlAttribute()
    {
        return $this->getImageUrlAttribute();
    }

    public function getOpenGraphImageUrlAttribute()
    {
        return $this->open_graph_image
            ? (env('APP_IMAGE_URL') . '/offers/' . $this->open_graph_image)
            : null;
    }
}
