<?php

namespace Tests\Utils;

class FakeUKPhoneNumber extends \Faker\Provider\Base
{
  public function ukPhoneNumber()
  {
    $str =  "+447";
    for ($i =0; $i < 9; $i++) {
        $str .= rand(0, 9);
    }
    return $str;
  }
}