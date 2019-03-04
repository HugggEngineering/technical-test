<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * A record of a push that was sent out from the CMS
 */
class Push extends Model {

    use Traits\UuidTrait;

	protected $table = 'pushes';
	public $timestamps = true;

	protected $fillable = [
        'text'
    ];

    /**
     * The list of users this push was sent to
     */
	public function users()
    {
        return $this->belongsToMany(User::class, 'pushes_users', 'push_id', 'user_id');
    }
}
