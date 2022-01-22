<?php
namespace Moviescan {
	/**
	 * Runtime environment related functions
	 */
	class Environment {
		public static function validate() {
			if(version_compare(phpversion(), "7.4.0", "<=")) die("PHP >= 7.4.0 is required to run.\n");
			if(!is_dir(SRC.DS."vendor")) die("vendor directory does not exists, perhaps you forgot 'composer install'?\nIf you're not a developer and are seeing this message, please raise an issue at github.\n");
		}
	}
}