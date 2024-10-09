<?php

namespace App\Helpers;

class ImportHelper
{

  public static function capitalize($string)
  {
    return ucwords(strtolower($string));
  }

  public static function findColNumber($cols, $name)
  {
    return array_search(self::capitalize($name), array_map(function ($el) {
      return self::capitalize($el);
    }, $cols));
  }
}
