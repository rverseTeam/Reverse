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
	 * Storage for the parsed ParamPack.
	 * @var array
	 */
	private static $paramPack = [];

	/**
	 * Unpacks the ParamPack to an array.
	 */
	public static function unpack() : void {
		$data = explode("\\", base64_decode($_SERVER["HTTP_X_NINTENDO_PARAMPACK"]));
	}
}