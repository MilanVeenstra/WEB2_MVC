<?php

namespace Framework\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface

class ServerRequest implements ServerRequestInterface
{
    /*
     * Recursively convert a single part of the $_FILES array to the corresponding
     * part of the array for getUploadedFiles
     */
    private function normalizeFiles(array $files): array|UploadedFileInterface
    {
        if (!isset($files['tmp_name'])) {
            return $files;
        } elseif (!is_array($files['tmp_name'])) {
            // the $files array represents a single uploaded file
            return new UploadedFile($files);
        } else {
            // the $files array represents an array of uploaded files
            // swap the first two levels to normalize the structure and
            // convert the array recursively
            $result = [];
            foreach ($files as $k => $v) {
                foreach ($v as $k2 => $v2) {
                    $result[$k2][$k] = $v2;
                }
            }
            return array_map([$this, 'normalizeFile'], $result);
        }
    }

    /*
     * Convert $_FILES array to PSR-7 compliant array structure for getUploadedFiles.
     */
    private function convertFilesToUploadedFiles(array $files): array
    {
        return array_map([$this, 'normalizeFiles'], $files);
    }
}
