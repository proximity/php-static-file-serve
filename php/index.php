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
$fullPath = $folder . $file;
$searchFiles = array();
if (is_dir($fullPath))
{
	// Requested path is a folder. If there is no trailing slash, add
	// one so that assets with relative URLs are loaded correctly.
	if (substr($_SERVER['REQUEST_URI'], 0, -1) !== '/')
	{
		$url = (empty($_SERVER['https'])) ? 'http://' : 'https://';
		$url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '/';
		header('Location: ' . $url);
		die();
	}

	// Remove all trailing slashes.
	while (substr($fullPath, 0, -1) === '/')
	{
		$fullPath = substr($fullPath, 0, strlen($fullPath) - 1);
	}

	// Add all "default" files to the search array.
	foreach ($config['default_files'] as $default)
	{
		$searchFiles[] = $fullPath . '/' . $default;
	}
}
else
{
	// Requested path points to a file, so look directly for that file.
	$searchFiles[] = $fullPath;
}

// Set $file to null until we've found a matching file.
$file = null;

// Loop through $searchFiles until we find a file that exists.
foreach($searchFiles as $searchFile)
{
	if (file_exists($searchFile) && !is_dir($searchFile))
	{
		$file = $searchFile;
		break;
	}
}

// If file is still null, it didn't find any files. Send 404 error.
if (!$file)
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
