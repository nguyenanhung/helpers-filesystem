<?php
/**
 * Project helpers-files
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 08/08/2021
 * Time: 22:20
 */

namespace nguyenanhung\Classes\Helper\Filesystem;

use DateTime;
use Exception;
use SplFileInfo;
use TheSeer\DirectoryScanner\DirectoryScanner;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

if (!class_exists('nguyenanhung\Classes\Helper\Filesystem\Filesystem')) {
    /**
     * Class Filesystem
     *
     * @package   nguyenanhung\Classes\Helper\Filesystem
     * @author    713uk13m <dev@nguyenanhung.com>
     * @copyright 713uk13m <dev@nguyenanhung.com>
     */
    class Filesystem extends SymfonyFilesystem implements ProjectInterface
    {
        use VersionTrait;

        /** @var null|array Mảng dữ liệu chứa các thuộc tính cần quét */
        private $scanInclude = ['*.log', '*.txt'];
        /** @var null|array Mảng dữ liệu chứa các thuộc tính bỏ qua không quét */
        private $scanExclude = ['*/Zip-Archive/*.zip'];

        /**
         * Hàm quét thư mục và list ra danh sách các file con
         *
         * @author: 713uk13m <dev@nguyenanhung.com>
         * @time  : 10/17/18 10:19
         *
         * @param string     $path    Đường dẫn thư mục cần quét, VD: /your/to/path
         * @param null|array $include Mảng dữ liệu chứa các thuộc tính cần quét
         * @param null|array $exclude Mảng dữ liệu chứa các thuộc tính bỏ qua không quét
         *
         * @see   https://github.com/theseer/DirectoryScanner/blob/master/samples/sample.php
         *
         * @return \Iterator
         */
        public function directoryScanner($path = '', $include = NULL, $exclude = NULL)
        {
            $scanner = new DirectoryScanner();
            if (is_array($include) && !empty($include)) {
                foreach ($include as $inc) {
                    $scanner->addInclude($inc);
                }
            }
            if (is_array($exclude) && !empty($exclude)) {
                foreach ($exclude as $exc) {
                    $scanner->addExclude($exc);
                }
            }

            return $scanner($path);
        }

        /**
         * Function setInclude
         *
         * @author: 713uk13m <dev@nguyenanhung.com>
         * @time  : 10/17/18 10:23
         *
         * @param array $include
         */
        public function setInclude($include = [])
        {
            $this->scanInclude = $include;
        }

        /**
         * Function setExclude
         *
         * @author: 713uk13m <dev@nguyenanhung.com>
         * @time  : 10/17/18 10:23
         *
         * @param array $exclude
         */
        public function setExclude($exclude = [])
        {
            $this->scanExclude = $exclude;
        }

        /**
         * Hàm xóa các file Log được chỉ định
         *
         * @author: 713uk13m <dev@nguyenanhung.com>
         * @time  : 10/17/18 10:21
         *
         * @param string $path     Thư mục cần quét và xóa
         * @param int    $dayToDel Số ngày cần giữ lại file
         *
         * @return array Mảng thông tin về các file đã xóa
         */
        public function cleanLog($path = '', $dayToDel = 3)
        {
            try {
                $getDir             = $this->directoryScanner($path, $this->scanInclude, $this->scanExclude);
                $result             = [];
                $result['scanPath'] = $path;
                foreach ($getDir as $fileName) {
                    $SplFileInfo = new SplFileInfo($fileName);
                    $filename    = $SplFileInfo->getPathname();
                    $format      = 'YmdHis';
                    // Lấy thời gian xác định xóa fileName
                    $dateTime   = new DateTime("-" . $dayToDel . " days");
                    $deleteTime = $dateTime->format($format);
                    // Lấy modifyTime của file
                    $getfileTime = filemtime($filename);
                    $fileTime    = date($format, $getfileTime);
                    if ($fileTime < $deleteTime) {
                        $this->chmod($filename, 0777);
                        $this->remove($filename);
                        $result['listFile'][] .= "Delete file: " . $filename;
                    }
                }

                return $result;
            }
            catch (Exception $e) {
                if (function_exists('log_message')) {
                    // Save Log if use CodeIgniter Framework
                    log_message('error', 'Error Message: ' . $e->getMessage());
                    log_message('error', 'Error Trace As String: ' . $e->getTraceAsString());
                }

                return NULL;
            }
        }

        /**
         * Hàm xóa các file Log được chỉ định
         *
         * @author: 713uk13m <dev@nguyenanhung.com>
         * @time  : 10/17/18 10:21
         *
         * @param string $path     Thư mục cần quét và xóa
         * @param int    $dayToDel Số ngày cần giữ lại file
         *
         * @return array Mảng thông tin về các file đã xóa
         */
        public function removeLog($path = '', $dayToDel = 3)
        {
            return $this->cleanLog($path, $dayToDel);
        }
    }
}
