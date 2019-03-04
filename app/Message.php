<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    const NAME = 'messages';

    use Traits\UuidTrait;
    use Traits\Encryptable;

    protected $table = 'messages';
    public $timestamps = true;

    protected $encryptable = [
        'message'
    ];

    protected $fillable = [
        'message',
        'message_type',
        'hug_id'
    ];

    public function hug()
    {
        return $this->belongsTo(Hug::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class);
    }


}
