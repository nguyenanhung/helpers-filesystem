<?php
/**
 * Project helpers-files
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 08/08/2021
 * Time: 22:43
 */
if (!function_exists('is_php')) {
    /**
     * Determines if the current version of PHP is equal to or greater than the supplied value
     *
     * @param string
     *
     * @return    bool    TRUE if the current version is $version or higher
     */
    function is_php($version)
    {
        static $_is_php;
        $version = (string) $version;

        if (!isset($_is_php[$version])) {
            $_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
        }

        return $_is_php[$version];
    }
}
if (!function_exists('string_to_path')) {
    /**
     * Function string_to_path - Combine multiple strings to a path.
     *
     * @param $paths
     *
     * @return array|string|string[]|null
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/08/2021 54:11
     */
    function string_to_path($paths)
    {
        if (!is_array($paths)) {
            $paths = func_get_args();
        }

        $path = implode(DIRECTORY_SEPARATOR, $paths);
        $path = preg_replace(
            '#' . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR . '+#',
            DIRECTORY_SEPARATOR,
            $path
        );

        return preg_replace('#([' . DIRECTORY_SEPARATOR . ']+$)#', '', $path);
    }
}