<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model {

    const NAME = 'stores';

    use Traits\UuidTrait;

	protected $table = 'stores';
	public $timestamps = true;

	protected $fillable = [
        'latitiude', 'longitude', 'address1', 'address2', 'address3', 'city', 'county', 'country', 'postcode', 'phone', 'email', 'website', 'name', 'description', 'image_filename', 'brand_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'image_filename'
    ];

    protected $appends = [
        'image',
        'image_url'
    ];

	public function brand()
    {
        return $this->belongsTo(Brand::class)->withTrashed();
    }

    public function getImageUrlAttribute()
    {
        return env('APP_IMAGE_URL') . '/locations/' . $this->image_filename;
    }
}
