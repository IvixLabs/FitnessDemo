<?php

namespace App\Service\FitnessClient;


use App\Service\FitnessClientPhotoServiceInterface;

class FileSystemFitnessClientPhotoService implements FitnessClientPhotoServiceInterface
{
    private $fitnessClientPhotoDir;

    public function __construct(
        string $fitnessClientPhotoDir
    ) {
        $this->fitnessClientPhotoDir = $fitnessClientPhotoDir;
    }

    /**
     * @param string $id
     * @param string $srcPath
     *
     * @return boolean
     */
    public function copyFitnessClientPhoto(string $id, string $srcPath)
    {
        if (is_readable($srcPath)) {
            copy($srcPath, $this->getPath($id));
            return true;
        }

        return false;
    }

    public function getFitnessClientPhotoPath(string $id)
    {
        $path = $this->getPath($id);
        if (is_readable($path)) {
            return $path;
        }

        throw new PhotoNotFoundException();
    }

    /**
     * @param string $id
     *
     * @return boolean
     */
    public function removeFitnessClientPhotoFile(string $id)
    {
        $path = $this->getPath($id);
        if (is_readable($path)) {
            unlink($path);
            return true;
        }

        return false;
    }

    private function getPath(string $id)
    {
        return $this->fitnessClientPhotoDir.DIRECTORY_SEPARATOR.$id;
    }
}