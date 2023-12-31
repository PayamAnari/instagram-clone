<?php

namespace App\Services;

use Image;
class FileService
{ 
    public function updateFile($model, $request, $type)
    {
        if(!empty($model->file)) {
          $currentFile = public_path() . $model->file;

          if(file_exists($currentFile) && $currentFile != public_path() . '/user-placeholder.png') {
            unlink($currentFile);
          }
        }

        $file = null;
    }
}