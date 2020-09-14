<?php

if (!function_exists('webp')) {
  function webp()
  {
    return function_exists('imagewebp');
  }
}