<?php
namespace Moviescan {
	class Input {
		/**
		 * Prints and reads from STDIN
		 * @param string $prompt A message to prompt the user
		 * @return string user input to STDIN
		 */
		public static function ask(string $prompt = null) {
			print $prompt;

			$handle = fopen("php://stdin","r");
			$output = fgets($handle);

			return trim($output);
		}

		/**
		 * Interprets a given answer as a boolean value
		 * If user does not provide any answer, aka presses enter
		 * The default value will be returned.
		 * @param string $input The user input to interpret
		 * @param string $default The default value to use, if no input
		 * @return boolean|null
		 */
		public static function interpretAnswer(string $input, ?bool $default = null) {
			if(preg_match("/^[yY](es)?/", $input)) {
				return true;
			} elseif(preg_match("/^[nN]o?/", $input)) {
				return false;
			}

			return $input === '' ? $default : null;
		}
	}
}