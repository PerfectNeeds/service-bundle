<?php

namespace PN\ServiceBundle\Lib;

/**
 * get upload Path
 *
 * @author Peter Nassef <peter.nassef@perfectneeds.com>
 */
class UploadPath
{

    /**
     * Return web root web/ or public_html/
     * @return string
     */
    public static function getWebRoot(): string
    {
        if (file_exists(realpath(__DIR__.'/../../../../public_html'))) {
            return self::addlSash("public_html");
        } elseif (file_exists(realpath(__DIR__.'/../../../../public'))) {
            return self::addlSash("public");
        } else {
            return self::addlSash("web");
        }
    }

    /**
     * Get the absolute path of the <b>upload</b> directory
     *
     * @param string $directory
     * @return string
     */
    public static function getUploadRootDir(string $directory): string
    {
        // the absolute directory extension where uploaded
        // documents should be saved
        return self::getRootDir().self::getUploadDir($directory);
    }

    /**
     * Get the absolute path of the <b>web</b> directory
     * @param string $directory
     * @param boolean $createIfNotExist
     * @return string
     */
    public static function getRootDir(string $directory = null, bool $createIfNotExist = false): string
    {
        // the absolute directory extension where uploaded
        // documents should be saved
        $path = __DIR__.'/../../../../'.self::getWebRoot();
        if ($directory !== null) {
            $path .= self::addlSash($directory);
        }
        if ($createIfNotExist == true and !file_exists($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }

    /**
     * Get Upload folder name
     *
     * @param string $directory
     * @return string
     */
    public static function getUploadDir(string $directory): string
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return self::addlSash('uploads/'.$directory);
    }

    /**
     * Add forward slash to directory
     *
     * @param string $directory
     * @return string
     */
    private static function addlSash(string $directory): string
    {
        return rtrim($directory, '/').'/';
    }

}
