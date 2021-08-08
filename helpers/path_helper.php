<?php
/**
 * Project helpers-files
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 08/08/2021
 * Time: 22:31
 */
/**
 * CodeIgniter Path Helpers
 *
 * @package        CodeIgniter
 * @subpackage     Helpers
 * @category       Helpers
 * @author         EllisLab Dev Team
 * @link           https://codeigniter.com/user_guide/helpers/path_helper.html
 */

// ------------------------------------------------------------------------

if (!function_exists('set_realpath')) {
    /**
     * Set Realpath
     *
     * @param string
     * @param bool    checks to see if the path exists
     *
     * @return    string
     */
    function set_realpath($path, $checkExistance = FALSE)
    {
        // Security check to make sure the path is NOT a URL. No remote file inclusion!
        if (preg_match('#^(http:\/\/|https:\/\/|www\.|ftp|php:\/\/)#i', $path) or filter_var($path, FILTER_VALIDATE_IP) === $path) {
            return 'The path you submitted must be a local server path, not a URL';
        }

        // Resolve the path
        if (realpath($path) !== FALSE) {
            $path = realpath($path);
        } elseif ($checkExistance && !is_dir($path) && !is_file($path)) {
            return 'Not a valid path: ' . $path;
        }

        // Add a trailing slash, if this is a directory
        return is_dir($path) ? rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : $path;
    }
}