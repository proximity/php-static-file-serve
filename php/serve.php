<?php
require_once('config.php');

$file = $_SERVER['REQUEST_URI'];
$folder = $config['folder_location'];

if (!is_dir($folder))
{
	throw new Exception("'$folder' is not a folder");
}

// Remove trailing slash(es) from folder
while (substr($folder, 0, -1) === '/')
{
	$folder = substr($folder, 0, strlen($folder) - 1);
}

// If double-dots are found in filename or filename is empty, 404
if (!$file || strpos($file, '..') !== false)
{
	header("HTTP/1.0 404 Not Found");
	die("404 Not Found");
}

// Make sure there is a slash at the beginning of the string
if (substr($file, 0, 1) !== '/')
{
	$file = '/' . $file;
}

// Concatenate folder and file, and check it exists
$file = $folder . $file;
if (!file_exists($file))
{
	header("HTTP/1.0 404 Not Found");
	die("404 Not Found");
}

// Get/set mime type so we can serve the file correctly
$finfo = new finfo(FILEINFO_MIME, $config['magic_file']);
$mime = $finfo->file();
finfo_close($finfo);
header('Content-type: ' . $mime);

// Output the file to the browser
readfile($file);
