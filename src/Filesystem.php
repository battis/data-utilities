<?php

namespace Battis\DataUtilities;

class Filesystem
{
  public static function delete(string $path, $deleteContents = false): bool
  {
    if (!$deleteContents) {
      if (!is_dir($path)) {
        return unlink($path);
      } elseif (is_dir($path)) {
        return rmdir($path);
      }
    } else {
      return Filesystem::recursiveDelete($path);
    }
  }

  private static function recursiveDelete(string $path)
  {
    if (!is_dir($path)) {
      return unlink($path);
    } elseif (is_dir($path)) {
      foreach (scandir($path) as $item) {
        if ($item !== "." && $item !== "..") {
          Filesystem::recursiveDelete(Path::join($path, $item));
        }
      }
      return rmdir($path);
    }
  }
}
