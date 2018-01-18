<?php
/**
 * Holds the ParamPack Helper.
 * @package Miiverse
 */

namespace Miiverse\Helpers;

/**
 * Handles the ParamPack header sent by Miiverse.
 * @package Miiverse
 * @author Repflez
 */
class ParamPack {
	/**
	 * Unpacks the ParamPack to an array.
	 */
	public static function unpack() {
		if (!isset($_SERVER['HTTP_X_NINTENDO_PARAMPACK'])) {
			die('TestVerse is only for 3DS consoles.');
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
	}
}