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
 * Interface Environment
 *
 * @package   nguyenanhung\Classes\Helper\Filesystem
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
interface Environment
{
    const VERSION       = '1.0.5';
    const LAST_MODIFIED = '2021-09-20';
    const AUTHOR_NAME   = 'Hung Nguyen';
    const AUTHOR_EMAIL  = 'dev@nguyenanhung.com';
    const AUTHOR_URL    = 'https://nguyenanhung.com';
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
