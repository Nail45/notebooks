<?php
// app/Helpers/helpers.php

use App\Helpers\ImageHelper;

if (!function_exists('notebook_image')) {
  function notebook_image($slug)
  {
    return ImageHelper::getFirstNotebookImage($slug);
  }
}
