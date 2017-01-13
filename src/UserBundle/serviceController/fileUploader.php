<?php
/**
 * Created by PhpStorm.
 * User: dieu
 * Date: 13/01/2017
 * Time: 11:44
 */

namespace UserBundle\serviceController;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class fileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->targetDir, $fileName);
        return $fileName;
    }
}