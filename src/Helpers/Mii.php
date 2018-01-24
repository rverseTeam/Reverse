<?php
/**
 * Holds the Mii Helper.
 * @package Miiverse
 */

namespace Miiverse\Helpers;

use SimpleXMLElement;

/**
 * Handles fetcing data from Nintendo about valid Netowrk IDs.
 * @package Miiverse
 * @author Repflez
 */
class Mii {

	private const URL = 'https://accountws.nintendo.net/v1/api/';

	/**
	 * Checks if the Nintendo Network ID is valid
	 * @var string $nnid
	 * @return bool
	 */
	public static function check(string $nnid) : bool {
		$mapped_ids = self::call(static::URL . 'admin/mapped_ids?input_type=user_id&output_type=pid&input=' . $nnid);

		if (!$mapped_ids->mapped_id->out_id) {
			return false;
		}

		return true;
	}

	/**
	 * Gets the mapped ID of the Nintendo Network ID supplied
	 * @var string $nnid
	 */
	public static function get(string $nnid) {
		$mapped_ids = self::call(static::URL . 'admin/mapped_ids?input_type=user_id&output_type=pid&input=' . $nnid);

		if (!$mapped_ids->mapped_id->out_id) {
			return false;
		}

		return $mapped_ids->mapped_id->out_id;
	}

	/**
	 * Gets the Mii images from the mapped id
	 * @var string $id
	 * @return array
	 */
	public static function getMiiImages(string $id) : array {
		$miis = self::call(static::URL . 'miis?pids=' . $id);

		$store = [];

		$miis = json_decode(json_encode($miis));

		// Don't ask why, but this is how I got that Mii parsing code
		foreach ($miis->mii->images->image as $mii) {
			if ($mii->type == 'whole_body') continue;
			if ($mii->type == 'standard') continue;
			$store[$mii->type] = $mii->url;
		}

		return $store;
	}

	private static function call(string $url) {
		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => [
				"X-Nintendo-Client-ID: " . config('nintendo.client_id'),
				"X-Nintendo-Client-Secret: " . config('nintendo.client_secret')
			]
		]);

		$response = curl_exec($curl);

		return new SimpleXMLElement($response);

	}
}