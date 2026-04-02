<?php
// app/Helpers/ImageHelper.php

namespace App\Helpers;

class ImageHelper
{
  protected static $cache = [];

  public static function getFirstNotebookImage($slug)
  {
    // Возвращаем из кэша, если уже искали
    if (isset(self::$cache[$slug])) {
      return self::$cache[$slug];
    }

    // Путь к папке с изображениями
    $folder = public_path('storage/images/' . $slug);

    // Если папки нет - возвращаем заглушку
    if (!is_dir($folder)) {
      self::$cache[$slug] = asset('storage/images/default.jpg');
      return self::$cache[$slug];
    }

    // Ищем первое изображение
    $files = scandir($folder);

    foreach ($files as $file) {
      if ($file === '.' || $file === '..') continue;

      $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
      if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
        self::$cache[$slug] = asset('storage/images/' . $slug . '/' . $file);
        return self::$cache[$slug];
      }
    }

    // Ничего не нашли - заглушка
    self::$cache[$slug] = asset('storage/images/default.jpg');
    return self::$cache[$slug];
  }
}
