<?php

namespace App\Traits;

trait Encryptable
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable)) {
            try {
                $value = \Crypt::decrypt($value);
            } catch(\Exception $e){}
        }

        return $value;
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = \Crypt::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }

    public function attributesToArray()
    {
        $attributes = parent::attributesToArray(); 

        foreach ($this->encryptable as $key) {

            if (isset($attributes[$key])){
                    //Try catch in case any issues with legacy content
                    try {
                        $attributes[$key] = \Crypt::decrypt($attributes[$key]);
                    } catch(\Exception $e)
                    {
                        $attributes[$key] = $attributes[$key];
                    }
                    

            }
        }
        return $attributes;
    }
}
