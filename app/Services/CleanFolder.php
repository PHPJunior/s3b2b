<?php

namespace App\Services;

trait CleanFolder
{
    /**
     * @param string $folder
     * @return string
     */
    private function cleanFolder(string $folder): string
    {
        return '/' . trim(str_replace('..', '', $folder), '/');
    }
}
