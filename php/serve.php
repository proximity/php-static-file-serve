<?php
require_once('config.php');

if (!isset($_GET['file']))
{
	// This shouldn't happen unless your webserver is configured
	// incorrectly.
	header("HTTP/1.0 418 I'm a teapot");
	die("I'm a teapot");
}

$file = $_GET['file'];
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

// Get mime type so we can serve the file correctly
$finfo = new finfo(FILEINFO_MIME, $config['magic_folder']);
$mime = $finfo->file();
finfo_close($finfo);


