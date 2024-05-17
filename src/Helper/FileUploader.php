<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger) {
        $this->slugger = $slugger;
    }


    public function upload(UploadedFile $file, string $rawName, string $dest): string
    {
        $name = strtolower($this->slugger->slug($rawName . '-' . uniqid())) . '.' . $file->guessExtension();
        $file->move($dest, $name);

        return $name;
    }

}