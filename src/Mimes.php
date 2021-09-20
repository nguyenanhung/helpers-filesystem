<?php
/**
 * Project helpers-filesystem
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 08/18/2021
 * Time: 08:40
 */

namespace nguyenanhung\Classes\Helper\Filesystem;

if (!class_exists(\nguyenanhung\Classes\Helper\Filesystem\Mimes::class)) {
    class Mimes
    {
        /**
         * Function getMimes
         *
         * @return array|mixed
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/18/2021 41:38
         */
        public static function getMimes()
        {
            return DataRepository::getData('mimes');
        }

        /**
         * Get Mime by Extension
         *
         * Translates a file extension into a mime type based on config/mimes.php.
         * Returns FALSE if it can't determine the type, or open the mime config file
         *
         * Note: this is NOT an accurate way of determining file mime types, and is here strictly as a convenience
         * It should NOT be trusted, and should certainly NOT be used for security
         *
         * @param string $filename File name
         *
         * @return    string
         */
        public static function getMimeByExtension($filename)
        {
            $mimes = static::getMimes();

            $extension = strtolower(substr(strrchr($filename, '.'), 1));

            if (isset($mimes[$extension])) {
                return is_array($mimes[$extension])
                    ? current($mimes[$extension]) // Multiple mime types, just give the first one
                    : $mimes[$extension];
            }

            return false;
        }
    }
}