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

  public static function addRowToErrorMessages($errors)
  {
    $errorMessages = [];
    foreach ($errors->getMessages() as $key => $messages) {
      preg_match('/(\d+)\.([A-Za-z]*)/', $key, $matches);
      if (isset($matches[1])) {
        $rowIndex = ((int) $matches[1]) + 2; // Add 1 to make it 1-based index + 1 because of header row
        foreach ($messages as &$message) {
          $message = str_replace(':row', $rowIndex, $message);
          $message = str_replace($matches[0], $matches[2], $message);
          $errorMessages[] = $message;
        }
      }
    }


    return $errorMessages;
  }

  public static $errorMessages = [
    "in" => "The :attribute with value :input on row :row must be one of the following: :values",
    "required" => "The :attribute missing on row :row is required",
    "regex" => "The :attribute with :input on row :row is not valid",
    "integer" => "The :attribute with :input on row :row is not an integer",
    "string" => "The :attribute with :input on row :row is not a valid text",
    "exists" => "The :attribute with :input on row :row doesn't exist",
    "numeric" => "The :attribute with :input on row :row is not an numeric",
  ];
}
