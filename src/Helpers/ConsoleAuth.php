<?php
/**
 * Holds the ConsoleAuth Helper.
 * @package Miiverse
 */

namespace Miiverse\Helpers;

/**
 * Handles the data headers sent by Miiverse.
 * @package Miiverse
 * @author Repflez
 */
class ConsoleAuth {
	/**
	 * Checks the Console Auth
	 */
	public static function check() {
		if (!isset($_SERVER['HTTP_X_NINTENDO_PARAMPACK']) || !isset($_SERVER['HTTP_X_NINTENDO_FRIEND_PID'])) {
			echo config('general.name'), ' is only for 3DS consoles at the moment.';
			exit;
		}

		$storage = [];

		// https://github.com/foxverse/3ds/blob/5e1797cdbaa33103754c4b63e87b4eded38606bf/web/titlesShow.php#L37-L40
		$data = explode('\\', base64_decode($_SERVER['HTTP_X_NINTENDO_PARAMPACK']));
		array_shift($data);
		array_pop($data);

		$paramCount = count($data);

		for ($i = 0; $i < $paramCount; $i += 2) {
			$storage[$data[$i]] = $data[$i + 1];
		}

		$storage['title_id'] = base_convert($storage['title_id'], 10, 16);
		$storage['transferable_id'] = base_convert($storage['transferable_id'], 10, 16);

		return ['paramPack' => $storage, 'friendPID' => $_SERVER['HTTP_X_NINTENDO_FRIEND_PID']];
	}
}