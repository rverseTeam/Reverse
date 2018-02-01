<?php
/**
 * Holds the user object class.
 * @package Miiverse
 */

namespace Miiverse;

use Carbon\Carbon;
use Miiverse\Exceptions\NetAddressTypeException;
use Miiverse\Exceptions\NetInvalidAddressException;
use stdClass;

/**
 * Everything you'd ever need from a specific user.
 * @package Miiverse
 * @author Repeflez
 */
class User
{
	/**
	 * The User's ID.
	 * @var int
	 */
	public $id = 0;

	/**
	 * The user's username.
	 * @var string
	 */
	public $username = 'User';

	/**
	 * A cleaned version of the username.
	 * @var string
	 */
	public $usernameClean = 'user';

	/**
	 * The rank object of the user's main rank.
	 * @var Rank
	 */
	public $mainRank = null;

	/**
	 * The ID of the main rank.
	 * @var int
	 */
	public $mainRankId = 1;

	/**
	 * The index of rank objects.
	 * @var array
	 */
	public $ranks = [];

	/**
	 * The user's username color.
	 * @var string
	 */
	public $color = '';

	/**
	 * The IP the user registered from.
	 * @var string
	 */
	public $registerIp = '0.0.0.0';

	/**
	 * The IP the user was last active from.
	 * @var string
	 */
	public $lastIp = '0.0.0.0';

	/**
	 * A user's title.
	 * @var string
	 */
	public $title = '';

	/**
	 * The UNIX timestamp of when the user registered.
	 * @var int
	 */
	public $registered = 0;

	/**
	 * The UNIX timestamp of when the user was last online.
	 * @var int
	 */
	public $lastOnline = 0;

	/**
	 * The 2 character country code of a user.
	 * @var string
	 */
	public $country = 'XX';

	/**
	 * The raw bio of the user.
	 * @var string
	 */
	public $bio = '';

	/**
	 * The expertise of the user.
	 * @var int
	 */
	public $expertise = 0;

	/**
	 * The File id of the user's header.
	 * @var int
	 */
	public $header = 0;

	/**
	 * The systems the user has.
	 * @var int
	 */
	public $systems = 0;

	/**
	 * The user's Nintendo ID.
	 * @var string
	 */
	public $nnid = 'User';

	/**
	 * A cleaned version of the Nintendo ID.
	 * @var string
	 */
	public $nnidClean = 'user';

	/**
	 * Is this user active?
	 * @var bool
	 */
	public $activated = false;

	/**
	 * Is this user restricted?
	 * @var bool
	 */
	public $restricted = false;

	/**
	 * Has this user made at least a post already?
	 * @var bool
	 */
	public $posted = false;

	/**
	 * Has this user favorited anything at least once?
	 * @var bool
	 */
	public $favorited = false;

	/**
	 * The user's birthday.
	 * @var string
	 */
	private $birthday = '0000-00-00';

	/**
	 * The amount of posts this user has.
	 * @var int
	 */
	public $posts = 0;

	/**
	 * The amount of follows this user has.
	 * @var int
	 */
	public $follows = 0;

	/**
	 * The amount of followers this user has.
	 * @var int
	 */
	public $followers = 0;

	/**
	 * Mii holder for this user.
	 */
	public $mii;

	/**
	 * Holds the permission checker for this user.
	 * @var UserPerms
	 */
	public $perms;

	/**
	 * The User instance cache array.
	 * @var array
	 */
	protected static $userCache = [];

	/**
	 * Cached constructor.
	 * @param int|string $uid
	 * @param bool $forceRefresh
	 * @return User
	 */
	public static function construct($uid, bool $forceRefresh = false) : User {
		if ($forceRefresh || !array_key_exists($uid, self::$userCache)) {
			self::$userCache[$uid] = new User($uid);
		}

		return self::$userCache[$uid];
	}

	/**
	 * Create a new user.
	 * @param string $username
	 * @param string $nnid
	 * @param bool $active
	 * @param array $ranks
	 * @return User
	 */
	public static function create(string $username, string $nnid, bool $active = true, array $ranks = []) : User {
		$usernameClean = clean_string($username, true);
		$nnidClean = clean_string($nnid, true, false, '', true);

		// make sure the user is always in the primary rank
		$ranks = array_unique(array_merge($ranks, [0]));

		$userId = DB::table('users')->insertGetId([
				'username' => trim($username),
				'username_clean' => $usernameClean,
				'nnid' => str_replace(' ', '_', $nnid),
				'nnid_clean' => $nnidClean,
				'rank_main' => 0,
				'register_ip' => Net::pton(Net::ip()),
				'last_ip' => Net::pton(Net::ip()),
				'user_registered' => time(),
				'user_last_online' => 0,
				'user_country' => get_country_code(),
				'user_activated' => $active,
				'user_systems' => 1
			]);

		$user = static::construct($userId);
		$user->addRanks($ranks);
		$user->setMainRank($ranks[0]);

		return $user;
	}

	/**
	 * The actual constructor.
	 * @param int|string $userId
	 */
	private function __construct($userId) {
		$userRow = DB::table('users')->where('user_id', $userId)->orWhere('username_clean', clean_string($userId, true))->first();

		if ($userRow) {
			$this->id = intval($userRow->user_id);
			$this->username = $userRow->username;
			$this->usernameClean = $userRow->username_clean;
			$this->nnid = $userRow->nnid;
			$this->nnidClean = $userRow->nnid_clean;
			$this->mainRankId = intval($userRow->rank_main);
			$this->color = $userRow->user_color;
			$this->title = $userRow->user_title;
			$this->registered = intval($userRow->user_registered);
			$this->lastOnline = intval($userRow->user_last_online);
			$this->birthday = $userRow->user_birthday;
			$this->country = $userRow->user_country;
			$this->bio = $userRow->user_bio;
			$this->expertise = boolval($userRow->user_expertise);
			$this->systems = boolval($userRow->user_systems);
			$this->activated = boolval($userRow->user_activated);
			$this->restricted = boolval($userRow->user_restricted);
			$this->posted = boolval($userRow->posted);
			$this->favorited = boolval($userRow->favorited);
			$this->posts = intval($userRow->posts);
			$this->follows = intval($userRow->follow_count);
			$this->followers = intval($userRow->follow_back_count);
			$this->registerIp = Net::ntop($userRow->register_ip);
			$this->lastIp = Net::ntop($userRow->last_ip);

			$this->mii = get_object_vars(DB::table('mii_mappings')->where('user_id', $this->id)->first());
		}

		// Get all ranks
		$ranks = DB::table('user_ranks')->where('user_id', $this->id)->get(['rank_id']);

		// Get the rows for all the ranks
		foreach ($ranks as $rank) {
			// Store the database row in the array
			$this->ranks[$rank->rank_id] = Rank::construct($rank->rank_id);
		}

		// Check if ranks were set
		if (empty($this->ranks)) {
			// If not assign the fallback rank
			$this->ranks[1] = Rank::construct(1);
		}

		// Check if the rank is actually assigned to this user
		if (!array_key_exists($this->mainRankId, $this->ranks)) {
			$this->mainRankId = array_keys($this->ranks)[0];
			$this->setMainRank($this->mainRankId);
		}

		$this->mainRank = $this->ranks[$this->mainRankId];
		$this->colour = $this->colour ? $this->colour : $this->mainRank->colour;
		$this->title = $this->title ? $this->title : $this->mainRank->title;

		$this->perms = new UserPerms($this);
	}

	/**
	 * Get a Carbon object of the registration date.
	 * @return Carbon
	 */
	public function registerDate() : Carbon {
		return Carbon::createFromTimestamp($this->registered);
	}

	/**
	 * Get a Carbon object of the last online date.
	 * @return Carbon
	 */
	public function lastDate() : Carbon {
		return Carbon::createFromTimestamp($this->lastOnline);
	}

	/**
	 * Get the user's birthday.
	 * @param bool $age
	 * @return int|string
	 */
	public function birthday(bool $age = false) {
		// If age is requested calculate it
		if ($age) {
			// Create dates
			$birthday = date_create($this->birthday);
			$now = date_create(date('Y-m-d'));

			// Get the difference
			$diff = date_diff($birthday, $now);

			// Return the difference in years
			return (int) $diff->format('%Y');
		}

		// Otherwise just return the birthday value
		return $this->birthday;
	}

	/**
	 * Get the user's country.
	 * @param bool $long
	 * @return string
	 */
	public function country(bool $long = false) : string {
		return $long ? get_country_name($this->country) : $this->country;
	}

	/**
	 * Updates the last IP and online time of the user.
	 */
	public function updateOnline() : void {
		$this->lastOnline = time();
		$this->lastIp = Net::ip();

		DB::table('users')->where('user_id', $this->id)->update([
				'user_last_online' => $this->lastOnline,
				'last_ip' => Net::pton($this->lastIp),
			]);
	}

	/**
	 * Runs some checks to see if this user is activated.
	 * @return bool
	 */
	public function isActive() : bool {
		return $this->id !== 0 && $this->activated;
	}

	/**
	 * Add ranks to a user.
	 * @param array $ranks
	 */
	public function addRanks(array $ranks) : void {
		// Update the ranks array
		$ranks = array_diff(
			array_unique(
				array_merge(
					array_keys($this->ranks),
					$ranks
				)
			),
			array_keys($this->ranks)
		);

		// Save to the database
		foreach ($ranks as $rank) {
			$this->ranks[$rank] = Rank::construct($rank);

			DB::table('user_ranks')->insert([
					'rank_id' => $rank,
					'user_id' => $this->id,
				]);
		}
	}

	/**
	 * Remove a set of ranks from a user.
	 * @param array $ranks
	 */
	public function removeRanks(array $ranks) : void {
		// Current ranks
		$remove = array_intersect(array_keys($this->ranks), $ranks);

		// Iterate over the ranks
		foreach ($remove as $rank) {
			unset($this->ranks[$rank]);

			DB::table('user_ranks')->where('user_id', $this->id)->where('rank_id', $rank)->delete();
		}
	}

	/**
	 * Change the main rank of a user.
	 * @param int $rank
	 */
	public function setMainRank(int $rank) : void {
		$this->mainRankId = $rank;
		$this->mainRank = $this->ranks[$rank];

		DB::table('users')->where('user_id', $this->id)->update([
				'rank_main' => $this->mainRankId,
			]);
	}

	/**
	 * Check if a user has a certain set of rank.
	 * @param array $ranks
	 * @return bool
	 */
	public function hasRanks(array $ranks) : bool {
		// Check if the main rank is the specified rank
		if (in_array($this->mainRankId, $ranks)) {
			return true;
		}

		// If not go over all ranks and check if the user has them
		foreach ($ranks as $rank) {
			// We check if $rank is in $this->ranks and if yes return true
			if (in_array($rank, array_keys($this->ranks))) {
				return true;
			}
		}

		// If all fails return false
		return false;
	}

	/**
	 * Gets the user's proper (highest) hierarchy.
	 * @return int
	 */
	public function hierarchy() : int {
		return DB::table('ranks')->join('user_ranks', 'ranks.rank_id', '=', 'user_ranks.rank_id')->where('user_id', $this->id)->max('ranks.rank_hierarchy');
	}
}
