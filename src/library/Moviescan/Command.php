<?php
namespace Moviescan {
	use \Garden\Cli\Cli;
	use \Garden\Cli\TaskLogger;

	class Command {
		/**
		 * Hold the class instance.
		 */
		private static $instance = null;

		/**
		 * @var Garden\Cli\Cli
		 */
		private $cli;

		/**
		 * @var Garden\Cli\Args
		 */
		private $args;

		/**
		 * @var Garden\Cli\TaskLogger
		 */
		private $logger;

		/**
		* The constructor is private
		* to prevent initiation with outer code.
		*/
		private function __construct() {
			$this->logger = new TaskLogger;

			$this->cli = new Cli();
			$this->cli
			->description("Moviescan - A tool that helps you sort through your movie and series library and remove bad rated movies.")
			->opt("directory:d", "Specify the directory to scan for movie files.", true, "string")
			->opt("filetypes:f", "Manually specify supported file extensions. Can be either comma seperated or passed multiple times. Default: mp4, mkv.", false, "string[]")
			->opt("verbose:v", "Increase verbosity, up to 3 levels.", false, "int[]")
			->opt("silent:s", "Turn off output entirely, should be used in conjunction with something else.");
			
			/**
			 * @todo Look for a more elegant way to pass $argv here.
			 */
			$this->args = $this->cli->parse($GLOBALS["argv"]);
		}

		public function getArgs() {
			return $this->args;
		}

		public function getLogger() {
			return $this->logger;
		}

		/**
		 * Pass all function calls on to the cli lib.
		 * @param string $name Name of the called method
		 * @param array $args Arguments passed to the function
		 * @return mixed Whatever the cli lib returns
		 */
		public function __call(string $name, array $args) {
			return $this->args->$name(...$args);
		}

		/**
		* The object is created from within the class itself
		* only if the class has no instance.
		* @return object instance of Rehhoff\Moviescan\Inc\Commands
		*/
		public static function getInstance() {
			if (self::$instance == null) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}