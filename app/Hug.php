<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Hug extends Model
{
    use Traits\UuidTrait;

    const NAME = 'hugs';

    protected $table = 'hugs';
    public $timestamps = true;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'purchase_id',
        'tags',
        'type',
        'shortcode'
    ];

    protected $appends = [
        'image_url'
    ];

    /**
     * Adds pseudo-attribute 'image_url'. Set to appropriate type image
     * or falling back to purchase image_url, which can be null.
     *
     * @return mixed string | null
     */
    public function getImageUrlAttribute()
    {
        return $this->type === "golden" ?
            config('huggg.goldenHuggg.imageUrl') :
            ($this->purchase ? $this->purchase->image_url : null);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Price::class);
    }

    public function message()
    {
        return $this->hasOne(Message::class);
    }
}
