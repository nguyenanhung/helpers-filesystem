<?php
/**
 * Project helpers-filesystem
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 09/20/2021
 * Time: 20:52
 */
require_once __DIR__ . '/../vendor/autoload.php';

$tmpPath = __DIR__ . '/../tmp/';


echo "Helper create_new_folder -> Result: " . create_new_folder($tmpPath . '/create_new_folder') . PHP_EOL;
echo "Helper directory_list_files -> Result: " . json_encode(directory_list_files($tmpPath)) . PHP_EOL;
echo "Helper file_create -> Result: " . file_create($tmpPath . '/test/test-file.txt') . PHP_EOL;
echo "Helper file_append -> Result: " . file_append($tmpPath . '/test/test-file.txt', 'Push Test content') . PHP_EOL;
echo "Helper file_read -> Result: " . file_read($tmpPath . '/test/test-file.txt') . PHP_EOL;
echo "Helper get_file_info -> Result: " . json_encode(get_file_info($tmpPath . '/test/test-file.txt')) . PHP_EOL;
echo "Helper format_size_units -> Result: " . format_size_units(11111111) . PHP_EOL;
