<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UploadService
{
    public function uploadFile($file)
    {
        return Cloudinary::uploadFile($file->getRealPath(), ['folder' => 'medias']);
    }

    public function getUrl($publicId)
    {
        return Cloudinary::getUrl($publicId);
    }

    public function getSecureUrl($publicId)
    {
        return Cloudinary::secureUrl($publicId);
    }

    public function getFileType($publicId)
    {
        return Cloudinary::getFileType($publicId);
    }
}
