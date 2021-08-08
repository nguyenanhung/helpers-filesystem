<?php
/**
 * Project helpers-filesystem
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 08/08/2021
 * Time: 01:07
 */

namespace nguyenanhung\Classes\Helper\Filesystem;

/**
 * Interface ProjectInterface
 *
 * @package   nguyenanhung\Classes\Helper\Filesystem
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
interface ProjectInterface
{
    const VERSION       = '1.0.1';
    const LAST_MODIFIED = '2021-08-08';
    const AUTHOR_NAME   = 'Hung Nguyen';
    const AUTHOR_EMAIL  = 'dev@nguyenanhung.com';
    const PROJECT_NAME  = 'Helpers - Filesystem';

    /**
     * Function getVersion
     *
     * @return mixed
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 07/06/2021 44:19
     */
    public function getVersion();
}
