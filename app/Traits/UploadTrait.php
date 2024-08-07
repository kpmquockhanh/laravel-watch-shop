<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait UploadTrait
{
    private function doUpload($image, $entityId, $entityType, $isThumbnail = true)
    {
        $resp = [];
        $needPreview = in_array($entityType, ['product']) && $isThumbnail;
        if ($isThumbnail) {
            $images = \App\Models\Image::query()->where([
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'is_thumbnail' => true,
            ]);
            foreach ($images as $image) {
                Storage::delete($image->src);
            }
            $images->delete();
        }

        $timeNow = time();
        $ext = $image->getClientOriginalExtension();
        $oName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $originName = "$timeNow-$oName-$entityType-$entityId.$ext";

        $imageOrigin = Image::make($image)
            ->insert('img/watermark.png', 'top-left', 20, 20);
        $imageOrigin = $imageOrigin->stream();
        Storage::put($originName, $imageOrigin->__toString());
        // For origin
        \App\Models\Image::query()->create([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'is_thumbnail' => $isThumbnail,
            'src' => $originName,
        ]);

        $resp[] = $originName;

        // TODO For preview plan for future
        if ($needPreview) {
            $imageThumb = Image::make($image)
                ->resize(null, 225, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->crop(225, 225)
                ->insert('img/watermark.png', 'top-left', 20, 20);

            $imageThumb = $imageThumb->stream();
            $name = "thumbs/$originName";
            Storage::put($name, $imageThumb->__toString());

            // For thumbnail
            \App\Models\Image::query()->create([
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'is_thumbnail' => $isThumbnail,
                'src' => $name,
            ]);

            $resp[] = $name;
        }
        return $resp;
    }
}
