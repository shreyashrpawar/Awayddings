<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ImageService
{
    /**
     * @param Model $model
     * @param UploadedFile $image
     * @param bool $deleteImage
     * @return void
     * @throws Exception
     */
    public function saveImage(Model $model, UploadedFile $image, bool $deleteImage = false): void
    {
        if ($deleteImage) {
            $this->deleteImage($model);
        }

        if ($image->isValid()) {
            $name = time() . $image->getClientOriginalName();
            $filePath = "images/{$model->getTable()}/$name";
            Storage::disk('s3')->put($filePath, file_get_contents($image), 'public');
            $model->image()->create(['url' => Storage::disk('s3')->url($filePath)]);
        } else {
            throw new Exception('File not valid', 422);
        }
    }

    /**
     * @param Model $model
     * @return mixed
     */
    private function deleteImage(Model $model): mixed
    {
        $name = basename($model->image->url);
        $filePath = "images/{$model->getTable()}/$name";
        Storage::disk('s3')->delete($filePath);
        return $model->image->delete();
    }
}
