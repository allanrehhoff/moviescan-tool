<?php
namespace Moviescan {
	/**
	 * Prints verbose message if needed
	 */
	class Verbosity {
		/**
		 * @var int Verbosity level -1: Total silence
		 */
		const SILENT = -1;

		/**
		 * @var int Verbosity level 0: Errors only
		 */
		const ERROR = 0;

		/**
		 * @var int Verbosity level 1: Informational
		 */
		const INFO = 1;
	
		/**
		 * @var int Verbosity level 2: Debug
		 */
		const DEBUG = 2;
	
		/**
		 * @var int Verbosity level 3: Literally every detail
		 */
		const DETAILED = 3;

		/**
		 * Prints only informational level messages
		 * Only prints to sdtout if verbosity level is sufficient
		 * @return void
		 */
		public static function info(string $message) {
			if(self::getLevel() >= self::INFO) {
				Command::getInstance()->getLogger()->info($message);
			}
		}
		
		/**
		 * Prints only informational level messages
		 * Only prints to sdtout if verbosity level is sufficient
		 * @return void
		 */
		public static function debug(string $message) {
			if(self::getLevel() >= self::DEBUG) {
				Command::getInstance()->getLogger()->info($message);
			}
		}
		
		/**
		 * Prints only detail level messages
		 * Only prints to sdtout if verbosity level is sufficient
		 * @return void
		 */
		public static function detail(string $message) {
			if(self::getLevel() >= self::DETAILED) {
				Command::getInstance()->getLogger()->info($message);
			}
		}

		/**
		 * Print error level messages
		 * Always printed unless --silent, -s was passed
		 * @return void
		 */
		public static function error(string $message) {
			if(self::getLevel() >= self::ERROR) {
				Command::getInstance()->getLogger()->error($message);
			}
		}

		/**
		 * Get verbosity level
		 * @return int The level of verbosity
		 */
		public static function getLevel() {
			if(Command::getInstance()->getArgs()->getOpt("silent")) {
				$verbose = self::QUIET;
			} else {
				$verbose = Command::getInstance()->getArgs()->getOpt("verbose", 0);
			}

			return $verbose;
		}
	}
}