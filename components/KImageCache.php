<?php

class ImageCache
{
    const CACHEDIR = 'imagecache';  // relative to webroot

    public static function getPath($sourcePath, $presetName)
    {
        $path = self::getRelativePath($sourcePath, $presetName);
        return Yii::getPathOfAlias('webroot') . '/' . $path;
    }

    public static function getUrl($sourcePath, $presetName)
    {
        $path = self::getRelativePath($sourcePath, $presetName);
        return Yii::app()->baseUrl . '/' . $path;
    }

    public static function getRelativePath($sourcePath, $presetName)
    {
        if (!is_file($sourcePath))
            throw new CException('Source file does not exists.');

        if (!$fileChangeTime = @filemtime($sourcePath))
            $fileChangeTime = time();

        $hash = md5($sourcePath . $presetName . $fileChangeTime);
        $filename = $hash;
        if (!is_null($extension = KFileHelper::extension($sourcePath))) {
             $filename .= ".{$extension}";
        }
        return self::CACHEDIR . '/' . $filename;
    }
}