<?php
/**
 * Holds the ConsoleAuth Helper.
 * @package Miiverse
 */

namespace Miiverse\Helpers;

use stdClass;

/**
 * Handles the data headers sent by Miiverse.
 * @package Miiverse
 * @author Repflez
 */
class ConsoleAuth {
	/**
	 * The ParamPack of the current console.
	 * @var array
	 */
	public static $paramPack = [];

	/**
	 * The friend PID of the current console.
	 * @var object
	 */
	public static $consoleId;

	/**
	 * Checks the Console Auth
	 */
	public static function check() {
		if (!isset($_SERVER['HTTP_X_NINTENDO_PARAMPACK']) || !isset($_SERVER['HTTP_X_NINTENDO_SERVICETOKEN'])) {
			redirect(route('welcome.guest') . '?src=menu');
		}

		if (!isset($_SESSION['authData'])) {
			$storage = [];

			// Unpack the ParamPack from the headers sent by the console
			// https://github.com/foxverse/3ds/blob/5e1797cdbaa33103754c4b63e87b4eded38606bf/web/titlesShow.php#L37-L40
			$data = explode('\\', base64_decode($_SERVER['HTTP_X_NINTENDO_PARAMPACK']));

			$paramCount = count($data);

			for ($i = 1; $i < $paramCount; $i += 2) {
				$storage[$data[$i]] = $data[$i + 1];
			}

			// Set title id and transferable id to hex, just in case we need it
			$storage['title_id'] = base_convert($storage['title_id'], 10, 16);
			$storage['transferable_id'] = base_convert($storage['transferable_id'], 10, 16);
			$serviceToken = bin2hex(base64_decode($_SERVER['HTTP_X_NINTENDO_SERVICETOKEN']));

			$_SESSION['authData'] = [
				'paramPack' => $storage,
				'short' => substr($serviceToken, 0, 16),
				'long' => substr($serviceToken, 0, 64),
			];
		}

		// Store the values for later use
		self::$paramPack = $_SESSION['authData']['paramPack'];
		self::$consoleId = new stdClass;

		self::$consoleId->short = $_SESSION['authData']['short'];
		self::$consoleId->long = $_SESSION['authData']['long'];
	}
}