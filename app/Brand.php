<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class Brand extends Model
{
    const NAME = 'brands';

    use SoftDeletes;
    use Traits\UuidTrait;

    protected $table = 'brands';
    public $timestamps = true;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'consolidated'
    ];

    public function locations()
    {
        return $this->hasMany(Store::class);
    }
}
