<?php
	/**
	 * Moviescan by A. Rehhoff
	 * A tool that helps you sort through your movie library and remove bad rated movies.
	 * 
	 * @package MovieScan
	 * @license	 http://opensource.org/licenses/MIT	MIT License
	 * @author A. Rehhoff
	 */

	use Nihilarr\PTN;
	use Moviescan\Input;
	use Moviescan\Command;
	use Moviescan\DiskCache;
	use Moviescan\Verbosity;
	use Moviescan\Filesystem;
	use Moviescan\Environment;

	/**
	 * @var string Current entry file directory
	 */
	define("SRC", __DIR__);

	/**
	 * @var string Shorthand for current system directory seperator
	 */
	define("DS", DIRECTORY_SEPARATOR);
	
	// Autoloader
	spl_autoload_register(function($class) {
		$classFile = str_replace("\\", DS, $class).".php";
		$filePath  = SRC.DS."library".DS.$classFile;

		if(file_exists($filePath)) require $filePath;
	});

	// Pre-flight checks
	Environment::validate();

	require SRC.DS."/vendor/autoload.php";

	$directory = Command::getInstance()->getArgs()->getOpt("directory", '.');

	Filesystem::checkDirectoryExists($directory);

	// Process all files
	Filesystem::forEachFile($directory, function(string $file, string $fullPath) {
		$parser = new Nihilarr\PTN();

		$movie = (object) $parser->parse($file);

		if(!Filesystem::isSupportedFileType($file))  {
			Verbosity::debug("Unsupported:: " . $file);
			return;
		}

		if(!isset($movie->title))  {
			Verbosity::info("Skipping: " . $file . " unable to parse movie title.");
			return;
		}

		Verbosity::detail("Found: " . $movie->title);
	});
	