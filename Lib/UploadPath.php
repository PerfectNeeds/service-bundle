<?php

namespace PNServiceBundle\Lib;

/**
 * get upload Path
 * 
 * @author Peter Nassef <peter.nassef@perfectneeds.com>
 */
class UploadPath {

    /**
     * Return web root web/ or public_html/
     * @return string
     */
    private static function getWebRoot() {
        if (file_exists(realpath(__DIR__ . '/../../../../../public_html'))) {
            return self::addlSash("public_html");
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
    public static function getUploadRootDir($directory) {
        // the absolute directory extension where uploaded
        // documents should be saved
        return self::getRootDir() . self::getUploadDir($directory);
    }

    /**
     * Get the absolute path of the <b>web</b> directory
     * @param string $directory
     * @param boolean $createIfNotExist
     * @return string
     */
    public static function getRootDir($directory = null, $createIfNotExist = false) {
        // the absolute directory extension where uploaded
        // documents should be saved
        $path = __DIR__ . '/../../../../../' . self::getWebRoot();
        if ($directory !== null) {
            $path .= self::addlSash($directory);
        }
        if ($createIfNotExist == true and ! file_exists($path)) {
            mkdir($path, 0777, TRUE);
        }
        return $path;
    }

    /**
     * Get Upload folder name
     * 
     * @param string $directory
     * @return string
     */
    public static function getUploadDir($directory) {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return self::addlSash('uploads/' . $directory);
    }

    /**
     * Add forward slash to directory
     * 
     * @param type $directory
     * @return string
     */
    private static function addlSash($directory) {
        return rtrim($directory, '/') . '/';
    }

}
