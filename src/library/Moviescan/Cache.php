<?php
/**
 * To be deleted.
 * @deprecated Do not use this class, it is broken and improperly written.
 */
namespace Moviescan {
	class Cache {
		public static function assertCacheDir(string $path) {
			$dirname = pathinfo($path, PATHINFO_DIRNAME);
			mkdir($dirname, 0777, true);
		}

		public static function getCacheDir() {
			return SRC.DS."cache";
		}

		private static function getPath($type, $filename) {
			$cacheDir = self::getCacheDir();
			$absCacheFile = $cacheDir.DS.$type.DS.$filename;
			return $absCacheFile;
		}

		public static function exists(string $cacheFile) {
			$cacheDir = self::getCacheDir();
			$absCacheFile = $cacheDir . DS . $cacheFile;

			return is_dir($cacheDir) && file_exists($absCacheFile);
		}

		private static function saveFile($filename, $data) {
			self::assertCacheDir($filename);
			return file_put_contents($filename, $data);
		}

		private static function getFile($filename) {

		}

		/**
		 * Saves raw data to a cache file
		 * @param string $cacheKey file name without extension
		 * @param string $data Data to save to disk
		 * @return int number of bytes written to disk
		 */
		public static function saveData(string $cacheKey, string $data) {
			$cacheFile = $cacheKey.".dat";
			$cacheFile = self::getPath("data", $cacheFile);

			return self::saveFile($cacheFile, $data);
		}

		public static function getData(string $cacheKey) {

		}

		/**
		 * Saves array of data as json file to the cache
		 * If file already exists, new data will be merged into existing data
		 * @param string $cacheKey file name without extension
		 * @param array $newData key/value pairs of data to save
		 */
		public static function saveJson(string $cacheKey, array $newData) {
			$cacheFile = $cacheKey.".json";
			$cacheFile = self::getPath("json", $cacheFile);

			if(self::exists($cacheFile)) {
				$cacheData = self::getJson($cacheFile);
				$data = array_merge($cacheData, $newData);
			} else {
				$data = $newData;
			}

			$json = json_encode($data);
			return self::saveFile($cacheFile, $json);
		}

		/**
		 * Get the decoded json result from a cache file
		 * @param string $cacheKey file name without extension
		 * @return array
		 */
		public static function getJson(string $cacheKey) {
			$cacheFile = $cacheKey.".json";
			
			if(!self::exists($cacheFile)) {
				return null;
			}

			$cacheFile = self::getPath("json", $cacheFile);

			$json = file_get_contents($cacheDir.DS.$cacheFile);

			return json_decode($json);
		}
	}
}