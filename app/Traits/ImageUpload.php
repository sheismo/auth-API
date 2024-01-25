<?php

namespace App\Traits;

trait ImageUpload
{
    public function imageUpload($image)
    {
    
            $fileNameWithExt = $image->getClientOriginalName();

        $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);

        $extension = $image->getClientOriginalExtension();

        $fileNameToStore = rand().''.time().''.$extension;

        $path = $image->move(public_path('images/'), $fileNameToStore);

        $postImage = "/images/".$fileNameToStore;

        return $postImage;
    }
}

?>