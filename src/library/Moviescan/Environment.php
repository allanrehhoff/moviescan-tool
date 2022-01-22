<?php
namespace Moviescan {
	/**
	 * Runtime environment related functions
	 */
	class Environment {
		public static function validate() {
			if(version_compare(phpversion(), "7.4.0", "<=")) die("PHP >= 7.4.0 is required to run.\n");
			if(!is_dir(SRC.DS."vendor")) die("vendor directory does not exists, perhaps you forgot 'composer install'?\nIf you're not a developer and are seeing this message, please raise an issue at github.\n");

			$credentials = DiskCache::get("data/credentials");

			if(!$credentials || !isset($credentials->tmdbApiKey)) {
				$apiKey = '';
				$saveKey = null;

				while(trim($apiKey) == '') {
					$apiKey = Input::ask("Please enter your TheMovieDatabase (TMDB) API key: ");
				}

				while($saveKey == null) {
					$answer = Input::ask("Save API key for later use? [Y/n]");
					$saveKey = Input::interpretAnswer($answer, true);
				}

				if($saveKey == true) {
					DiskCache::set("data/credentials", ["tmdbApiKey" => $apiKey]);
				}
			}
		}
	}
}