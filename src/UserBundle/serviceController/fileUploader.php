<?php
/**
 * Created by PhpStorm.
 * User: dieu
 * Date: 13/01/2017
 * Time: 11:44
 */

namespace UserBundle\serviceController;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use UserBundle\Entity\User;

class fileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * @param UploadedFile $file
     * @param User $user
     * @return string
     */
    public function upload(UploadedFile $file, User $user)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->targetDir, $fileName);
        $user->setProfilePictureFile($file);
        return $fileName;
    }
}