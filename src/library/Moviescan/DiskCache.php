<?php
	namespace Moviescan {
		class DiskCache {
			const EXPIRATION_TIME = 60 * 60 * 24 * 90;

			private static function getCacheDir() {
				return SRC.DS."cache";
			}

			/**
			 * Gets an absolute system path
			 * @return string An absolute filesystem pathname
			 */
			private static function getAbsolutePath($partial) {
				return self::getCacheDir().DS.$partial.'.json';
			}

			/**
			 * Determines if a a given cache entry has expired
			 * @param string $partial The cache key to compare
			 * @return bool Whether or not a cache entry has expired.
			 */
			public static function expired(string $partial) {
				$filename = self::getAbsolutePath($partial);
				$thresholdTime = filemtime($filename) + self::EXPIRATION_TIME;

				return $thresholdTime < time();
			}

			/**
			 * Checks if given cache key exists on the filesystem.
			 * Deleting expired cache entries prior to returning.
			 * @param string $partial The cache key to compare
			 * @return bool Whether or not the cache exists on the disk
			 */
			public static function exists(string $partial) {
				$filename = self::getAbsolutePath($partial);

				if(file_exists($filename) && self::expired($partial)) {
					unlink($filename);
				}

				return file_exists($filename);
			}

			/**
			 * Get the saved cache for a given key
			 * @param string $partial The cache key
			 * @return array|stdClass Depending on the structure of the saved data.
			 */
			public static function get(string $partial) {
				if(!self::exists($partial)) {
					return null;
				}

				$filename = self::getAbsolutePath($partial);
				$json = file_get_contents($filename);

				return json_decode($json);
			}

			/**
			 * Saves structured data to the disk cache.
			 * @param string $partial The cache key to use for filename
			 * @param array $newData The data structure to save
			 * @return int number of bytes written
			 */
			public static function set(string $partial, array $newData) {
				$filename = self::getAbsolutePath($partial);
				$directory = pathinfo($filename, PATHINFO_DIRNAME);

				if(!is_dir($directory)) {
					mkdir($directory, 0777, true);
				}

				if(self::exists($partial)) {
					$currentData = self::get($partial);
					$data = array_merge($currentData, $newData);
				} else {
					$data = $newData;
				}

				$json = json_encode($data);

				return file_put_contents($filename, $json);
			}
		}
	}