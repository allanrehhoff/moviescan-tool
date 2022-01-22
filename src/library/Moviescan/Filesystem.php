<?php
namespace Moviescan {

	/**
	 * Interacts with the current filesystem in various ways
	 */
	class Filesystem {
		/**
		 * Recursively applies a callback function
		 * to files in given directory.
		 * Callback function will be given to arguments
		 * - arg1 : $file string, basename of the current file
		 * - arg2 : $fullPath string, absolute path to the current file
		 * @return void
		 */
		public static function forEachFile(string $directory, callable $callback) {
			foreach(scandir($directory) as $inode) {
				if($inode == '.' || $inode == '..') continue;

				$fullPath = realpath($directory.DS.$inode);

				if(is_dir($fullPath) == true) {
					self::forEachFile($fullPath, $callback);
				} else {
					$callback($inode, $fullPath);
				}
			}
		}

		/**
		 * Checks if a given filename is supported
		 * @param string $file Filename to check against.
		 * @return boolean
		 */
		public static function isSupportedFileType(string $file) {
			$extension = pathinfo($file, PATHINFO_EXTENSION);
			$providedExtension = Command::getInstance()->getArgs()->getOpt("filetypes", ["mkv", "mp4"]);

			$supported = [];

			// Add support for comma seperated values
			// for those who don't know that cli arguments
			// can be passed multiple times in the same command.
			foreach($providedExtension as $entry) {
				$supported = array_merge($supported, explode(',', $entry));
			}

			return in_array($extension, $supported);
		}

		/**
		 * Checks that the given directory exists
		 * Logs and ends if missing on the filesystem.
		 * @return void
		 */
		public static function checkDirectoryExists(string $directory) {
			if(is_dir($directory) == false) {
				Verbosity::error($directory." : No such directory");
				exit;
			}
		}
	}
}