<?php

namespace App\Service;

interface FitnessClientPhotoServiceInterface
{

    /**
     * @param string $id
     * @param string $srcPath
     *
     * @return boolean
     */
    public function copyFitnessClientPhoto(string $id, string $srcPath);

    public function getFitnessClientPhotoPath(string $id);

    /**
     * @param string $id
     *
     * @return boolean
     */
    public function removeFitnessClientPhotoFile(string $id);
}