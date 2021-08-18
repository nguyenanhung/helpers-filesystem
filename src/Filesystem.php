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

        /**
         * Function formatSizeUnits
         *
         * @param int $bytes
         *
         * @return string
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/18/2021 32:57
         */
        public function formatSizeUnits($bytes = 0)
        {
            if ($bytes >= 1073741824) {
                $bytes = number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            } elseif ($bytes > 1) {
                $bytes = $bytes . ' bytes';
            } elseif ($bytes == 1) {
                $bytes = $bytes . ' byte';
            } else {
                $bytes = '0 bytes';
            }

            return $bytes;
        }

        /**
         * Function createNewFolder - Create new folder and put 3 files: index.html, .htaccess and README.md
         *
         * @param string $pathname
         * @param int    $mode
         *
         * @return bool
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/18/2021 32:15
         */
        public function createNewFolder($pathname = '', $mode = 0777)
        {
            if (is_null($pathname) || empty($pathname)) {
                return FALSE;
            }
            if (is_dir($pathname) || $pathname === "/") {
                return TRUE;
            }
            if (!is_dir($pathname) && strlen($pathname) > 0) {
                try {
                    $this->mkdir($pathname, $mode);
                    // Gen file Index.html + .htaccess
                    $fileContentIndex    = "<!DOCTYPE html>\n<html lang='vi'>\n<head>\n<title>403 Forbidden</title>\n</head>\n<body>\n<p>Directory access is forbidden.</p>\n</body>\n</html>";
                    $fileContentHtaccess = "RewriteEngine On\nOptions -Indexes\nAddType text/plain php3 php4 php5 php cgi asp aspx html css js";
                    $fileContentReadme   = "#" . $pathname . " README";
                    $this->appendToFile($pathname . DIRECTORY_SEPARATOR . 'index.html', $fileContentIndex);
                    $this->appendToFile($pathname . DIRECTORY_SEPARATOR . '.htaccess', $fileContentHtaccess);
                    $this->appendToFile($pathname . DIRECTORY_SEPARATOR . 'README.md', $fileContentReadme);

                    return TRUE;
                }
                catch (Exception $e) {
                    return FALSE;
                }
            }

            return FALSE;
        }

        /**
         * Tests for file writability
         *
         * is_writable() returns TRUE on Windows servers when you really can't write to
         * the file, based on the read-only attribute. is_writable() is also unreliable
         * on Unix servers if safe_mode is on.
         *
         * @link    https://bugs.php.net/bug.php?id=54709
         *
         * @param string
         *
         * @return    bool
         */
        public function isReallyWritable($file)
        {
            // If we're on a Unix server with safe_mode off we call is_writable
            if (DIRECTORY_SEPARATOR === '/' && (is_php('5.4') or !ini_get('safe_mode'))) {
                return is_writable($file);
            }

            /* For Windows servers and safe_mode "on" installations we'll actually
             * write a file then read it. Bah...
             */
            if (is_dir($file)) {
                $file = rtrim($file, '/') . '/' . md5(mt_rand());
                if (($fp = @fopen($file, 'ab')) === FALSE) {
                    return FALSE;
                }

                fclose($fp);
                @chmod($file, 0777);
                @unlink($file);

                return TRUE;
            } elseif (!is_file($file) or ($fp = @fopen($file, 'ab')) === FALSE) {
                return FALSE;
            }

            fclose($fp);

            return TRUE;
        }

        /**
         * Function readFile - Opens the file specified in the path and returns it as a string.
         *
         * @param $file
         *
         * @return false|string|null
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/18/2021 00:10
         */
        public function readFile($file)
        {
            if (file_exists($file)) {
                return file_get_contents($file);
            }

            return NULL;
        }

        /**
         * Write File
         *
         * Writes data to the file specified in the path.
         * Creates a new file if non-existent.
         *
         * @param string $path File path
         * @param string $data Data to write
         * @param string $mode fopen() mode (default: 'wb')
         *
         * @return    bool
         */
        public function writeFile($path, $data, $mode = 'wb')
        {
            if (!$fp = @fopen($path, $mode)) {
                return FALSE;
            }

            flock($fp, LOCK_EX);

            for ($result = $written = 0, $length = strlen($data); $written < $length; $written += $result) {
                if (($result = fwrite($fp, substr($data, $written))) === FALSE) {
                    break;
                }
            }

            flock($fp, LOCK_UN);
            fclose($fp);

            return is_int($result);
        }

        /**
         * Delete Files
         *
         * Deletes all files contained in the supplied directory path.
         * Files must be writable or owned by the system in order to be deleted.
         * If the second parameter is set to TRUE, any directories contained
         * within the supplied base directory will be nuked as well.
         *
         * @param string $path    File path
         * @param bool   $del_dir Whether to delete any directories found in the path
         * @param bool   $htdocs  Whether to skip deleting .htaccess and index page files
         * @param int    $_level  Current directory depth level (default: 0; internal use only)
         *
         * @return    bool
         */
        public function deleteFiles($path, $del_dir = FALSE, $htdocs = FALSE, $_level = 0)
        {
            // Trim the trailing slash
            $path = rtrim($path, '/\\');

            if (!$current_dir = @opendir($path)) {
                return FALSE;
            }

            while (FALSE !== ($filename = @readdir($current_dir))) {
                if ($filename !== '.' && $filename !== '..') {
                    $filepath = $path . DIRECTORY_SEPARATOR . $filename;

                    if (is_dir($filepath) && $filename[0] !== '.' && !is_link($filepath)) {
                        delete_files($filepath, $del_dir, $htdocs, $_level + 1);
                    } elseif ($htdocs !== TRUE or !preg_match('/^(\.htaccess|index\.(html|htm|php)|web\.config)$/i', $filename)) {
                        @unlink($filepath);
                    }
                }
            }

            closedir($current_dir);

            if (($del_dir === TRUE && $_level > 0)) {
                return @rmdir($path);
            } else {
                return TRUE;
            }
        }

        /**
         * Get Filenames
         *
         * Reads the specified directory and builds an array containing the filenames.
         * Any sub-folders contained within the specified path are read as well.
         *
         * @param string    path to source
         * @param bool    whether to include the path as part of the filename
         * @param bool    internal variable to determine recursion status - do not use in calls
         *
         * @return    array|bool
         */
        public function getFilenames($source_dir, $include_path = FALSE, $_recursion = FALSE)
        {
            static $_fileData = array();

            if ($fp = @opendir($source_dir)) {
                // reset the array and make sure $source_dir has a trailing slash on the initial call
                if ($_recursion === FALSE) {
                    $_fileData  = array();
                    $source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                }

                while (FALSE !== ($file = readdir($fp))) {
                    if (is_dir($source_dir . $file) && $file[0] !== '.') {
                        self::getFilenames($source_dir . $file . DIRECTORY_SEPARATOR, $include_path, TRUE);
                    } elseif ($file[0] !== '.') {
                        $_fileData[] = ($include_path === TRUE) ? $source_dir . $file : $file;
                    }
                }

                closedir($fp);

                return $_fileData;
            }

            return FALSE;
        }

        /**
         * Get Directory File Information
         *
         * Reads the specified directory and builds an array containing the filenames,
         * filesize, dates, and permissions
         *
         * Any sub-folders contained within the specified path are read as well.
         *
         * @param string    path to source
         * @param bool    Look only at the top level directory specified?
         * @param bool    internal variable to determine recursion status - do not use in calls
         *
         * @return    array|bool
         */
        public function getDirectoryFileInformation($source_dir, $top_level_only = TRUE, $_recursion = FALSE)
        {
            static $_fileData = array();
            $relative_path = $source_dir;

            if ($fp = @opendir($source_dir)) {
                // reset the array and make sure $source_dir has a trailing slash on the initial call
                if ($_recursion === FALSE) {
                    $_fileData  = array();
                    $source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                }

                // Used to be foreach (scandir($source_dir, 1) as $file), but scandir() is simply not as fast
                while (FALSE !== ($file = readdir($fp))) {
                    if (is_dir($source_dir . $file) && $file[0] !== '.' && $top_level_only === FALSE) {
                        self::getDirectoryFileInformation($source_dir . $file . DIRECTORY_SEPARATOR, $top_level_only, TRUE);
                    } elseif ($file[0] !== '.') {
                        $_fileData[$file]                  = $this->getFileInfo($source_dir . $file);
                        $_fileData[$file]['relative_path'] = $relative_path;
                    }
                }

                closedir($fp);

                return $_fileData;
            }

            return FALSE;
        }

        /**
         * Get File Info
         *
         * Given a file and path, returns the name, path, size, date modified
         * Second parameter allows you to explicitly declare what information you want returned
         * Options are: name, server_path, size, date, readable, writable, executable, fileperms
         * Returns FALSE if the file cannot be found.
         *
         * @param string    path to file
         * @param mixed    array or comma separated string of information returned
         *
         * @return array|false
         */
        public function getFileInfo($file, $returned_values = array('name', 'server_path', 'size', 'date'))
        {
            if (!file_exists($file)) {
                return FALSE;
            }

            if (is_string($returned_values)) {
                $returned_values = explode(',', $returned_values);
            }
            $fileInfo = array();
            foreach ($returned_values as $key) {
                switch ($key) {
                    case 'name':
                        $fileInfo['name'] = basename($file);
                        break;
                    case 'server_path':
                        $fileInfo['server_path'] = $file;
                        break;
                    case 'size':
                        $fileInfo['size'] = filesize($file);
                        break;
                    case 'date':
                        $fileInfo['date'] = filemtime($file);
                        break;
                    case 'readable':
                        $fileInfo['readable'] = is_readable($file);
                        break;
                    case 'writable':
                        $fileInfo['writable'] = $this->isReallyWritable($file);
                        break;
                    case 'executable':
                        $fileInfo['executable'] = is_executable($file);
                        break;
                    case 'fileperms':
                        $fileInfo['fileperms'] = fileperms($file);
                        break;
                }
            }

            return $fileInfo;
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
        public function getMimeByExtension($filename)
        {
            $mimes = Mimes::getMimes();

            $extension = strtolower(substr(strrchr($filename, '.'), 1));

            if (isset($mimes[$extension])) {
                return is_array($mimes[$extension])
                    ? current($mimes[$extension]) // Multiple mime types, just give the first one
                    : $mimes[$extension];
            }

            return FALSE;
        }

        /**
         * Symbolic Permissions
         *
         * Takes a numeric value representing a file's permissions and returns
         * standard symbolic notation representing that value
         *
         * @param int $perms Permissions
         *
         * @return    string
         */
        public function symbolicPermissions($perms)
        {
            if (($perms & 0xC000) === 0xC000) {
                $symbolic = 's'; // Socket
            } elseif (($perms & 0xA000) === 0xA000) {
                $symbolic = 'l'; // Symbolic Link
            } elseif (($perms & 0x8000) === 0x8000) {
                $symbolic = '-'; // Regular
            } elseif (($perms & 0x6000) === 0x6000) {
                $symbolic = 'b'; // Block special
            } elseif (($perms & 0x4000) === 0x4000) {
                $symbolic = 'd'; // Directory
            } elseif (($perms & 0x2000) === 0x2000) {
                $symbolic = 'c'; // Character special
            } elseif (($perms & 0x1000) === 0x1000) {
                $symbolic = 'p'; // FIFO pipe
            } else {
                $symbolic = 'u'; // Unknown
            }

            // Owner
            $symbolic .= (($perms & 0x0100) ? 'r' : '-')
                         . (($perms & 0x0080) ? 'w' : '-')
                         . (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms & 0x0800) ? 'S' : '-'));

            // Group
            $symbolic .= (($perms & 0x0020) ? 'r' : '-')
                         . (($perms & 0x0010) ? 'w' : '-')
                         . (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms & 0x0400) ? 'S' : '-'));

            // World
            $symbolic .= (($perms & 0x0004) ? 'r' : '-')
                         . (($perms & 0x0002) ? 'w' : '-')
                         . (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms & 0x0200) ? 'T' : '-'));

            return $symbolic;
        }

        /**
         * Octal Permissions
         *
         * Takes a numeric value representing a file's permissions and returns
         * a three character string representing the file's octal permissions
         *
         * @param int $perms Permissions
         *
         * @return    string
         */
        public function octalPermissions($perms)
        {
            return substr(sprintf('%o', $perms), -3);
        }

        /**
         * Function fileGetDirectory - Get name of the file's directory.
         *
         * @param $path
         *
         * @return array|string|string[]
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/18/2021 56:42
         */
        public function fileGetDirectory($path)
        {
            return pathinfo($path, PATHINFO_DIRNAME);
        }

        /**
         * Function fileGetExtension
         *
         * @param $path
         *
         * @return array|string|string[]
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/18/2021 57:39
         */
        public function fileGetExtension($path)
        {
            return pathinfo($path, PATHINFO_EXTENSION);
        }

        /**
         * Function fileGetBasename
         *
         * @param $path
         *
         * @return array|string|string[]
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/18/2021 59:10
         */
        public function fileGetBasename($path)
        {
            return pathinfo($path, PATHINFO_BASENAME);
        }

        /**
         * Function fileRead - alias of readFile method
         *
         * @param $file
         *
         * @return false|string|null
         * @author   : 713uk13m <dev@nguyenanhung.com>
         * @copyright: 713uk13m <dev@nguyenanhung.com>
         * @time     : 08/18/2021 00:14
         */
        public function fileRead($file)
        {
            return $this->readFile($file);
        }

        /**
         * Create a file and all necessary subdirectories.
         *
         * @param $path
         *
         * @return bool
         */
        public function fileCreate($path)
        {
            if (!file_exists($path)) {
                $dir = $this->fileGetDirectory($path);

                if (!is_dir($dir)) {
                    Directory::directoryCreate($dir);
                }

                return file_put_contents($path, '') !== FALSE;
            }

            return TRUE;
        }

        /**
         * Write to a file.
         *
         * @param $path
         * @param $content
         *
         * @return bool
         */
        public function fileWrite($path, $content)
        {
            $this->fileCreate($path);

            return file_put_contents($path, $content) !== FALSE;
        }

        /**
         * Append contents to the end of file.
         *
         * @param $path
         * @param $content
         *
         * @return bool
         */
        public function fileAppend($path, $content)
        {
            if (file_exists($path)) {
                return $this->fileWrite($path, $this->fileRead($path) . $content);
            }

            return $this->fileWrite($path, $content);
        }

        /**
         * Prepend contents to the beginning of file.
         *
         * @param $path
         * @param $content
         *
         * @return bool
         */
        public function filePrepend($path, $content)
        {
            if (file_exists($path)) {
                return $this->fileWrite($path, $content . $this->fileRead($path));
            }

            return $this->fileWrite($path, $content);
        }

        /**
         * Delete a file.
         *
         * @param $path
         *
         * @return bool
         */
        public function fileDelete($path)
        {
            if (file_exists($path)) {
                return unlink($path);
            }

            return TRUE;
        }

        /**
         * Move a file from one location to another and
         * create all necessary subdirectories.
         *
         * @param $oldPath
         * @param $newPath
         *
         * @return bool
         */
        public function file_move($oldPath, $newPath)
        {
            $dir = $this->fileGetDirectory($newPath);

            if (!directory_exists($dir)) {
                Directory::directoryCreate($dir);
            }

            return rename($oldPath, $newPath);
        }

        /**
         * Copy a file from one location to another
         * and create all necessary subdirectories.
         *
         * @param $oldPath
         * @param $newPath
         *
         * @return bool
         */
        public function fileCopy($oldPath, $newPath)
        {
            $dir = $this->fileGetDirectory($newPath);

            if (!is_dir($dir)) {
                Directory::directoryCreate($dir);
            }

            return copy($oldPath, $newPath);
        }

        /**
         * Rename file at the given path.
         *
         * @param $path
         * @param $newName
         *
         * @return bool
         */
        public function fileRename($path, $newName)
        {
            $newPath = string_to_path($this->fileGetDirectory($path), $newName);

            return rename($path, $newPath);
        }
    }
}
