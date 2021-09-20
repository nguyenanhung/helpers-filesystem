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
 * Trait Version
 *
 * @package   nguyenanhung\Classes\Helper\Filesystem
 * @author    713uk13m <dev@nguyenanhung.com>
 * @copyright 713uk13m <dev@nguyenanhung.com>
 */
trait Version
{
    /**
     * Function getVersion
     *
     * @return string
     * @author   : 713uk13m <dev@nguyenanhung.com>
     * @copyright: 713uk13m <dev@nguyenanhung.com>
     * @time     : 08/07/2021 07:03
     */
    public function getVersion()
    {
        return self::VERSION;
    }
}
