<?php
/**
 * Project helpers-files
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 08/08/2021
 * Time: 22:29
 */
/**
 * CodeIgniter Directory Helpers
 *
 * @package        CodeIgniter
 * @subpackage     Helpers
 * @category       Helpers
 * @author         EllisLab Dev Team
 * @link           https://codeigniter.com/user_guide/helpers/directory_helper.html
 */

// ------------------------------------------------------------------------

if (!function_exists('directory_map')) {
    /**
     * Create a Directory Map
     *
     * Reads the specified directory and builds an array
     * representation of it. Sub-folders contained with the
     * directory will be mapped as well.
     *
     * @param string $source_dir      Path to source
     * @param int    $directory_depth Depth of directories to traverse
     *                                (0 = fully recursive, 1 = current dir, etc)
     * @param bool   $hidden          Whether to show hidden files
     *
     * @return    array|bool
     */
    function directory_map($source_dir, $directory_depth = 0, $hidden = FALSE)
    {
        if ($fp = @opendir($source_dir)) {
            $fileData   = array();
            $newDepth   = $directory_depth - 1;
            $source_dir = rtrim($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            while (FALSE !== ($file = readdir($fp))) {
                // Remove '.', '..', and hidden files [optional]
                if ($file === '.' or $file === '..' or ($hidden === FALSE && $file[0] === '.')) {
                    continue;
                }

                is_dir($source_dir . $file) && $file .= DIRECTORY_SEPARATOR;

                if (($directory_depth < 1 or $newDepth > 0) && is_dir($source_dir . $file)) {
                    $fileData[$file] = directory_map($source_dir . $file, $newDepth, $hidden);
                } else {
                    $fileData[] = $file;
                }
            }

            closedir($fp);

            return $fileData;
        }

        return FALSE;
    }
}