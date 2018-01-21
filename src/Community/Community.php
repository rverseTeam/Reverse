<?php
/**
 * Holds the community object class.
 * @package Miiverse
 */

namespace Miiverse\Community;

use Miiverse\DB;
use Miiverse\CurrentSession;

/**
 * Used to serve communities.
 * @package Miiverse
 * @author Repflez
 */
class Community
{
    /**
     * The ID of the community.
     * @var int
     */
    public $id = 0;

    /**
     * The title ID of the community.
     * @var int
     */
    public $title_id = 0;

    /**
     * The name of the community.
     * @var string
     */
    public $name = "Community";

    /**
     * The description of the community.
     * @var string
     */
    public $description = "";

    /**
     * The icon of the community.
     * @var string
     */
    public $icon = "";

    /**
     * The banner of the community.
     * @var string
     */
    public $banner = "";

    /**
     * The ID of the parent community.
     * @var int
     */
    public $category = 0;

    /**
     * The type of community.
     * @var int
     */
    public $type = 0;

    /**
     * The creation date of the community.
     * @var string
     */
    public $created = '';

    /**
     * The platform of the community.
     * @var int
     */
    public $platform = 0;

    /**
     * Holds the permission handler.
     * @var mixed
     */
    public $perms;

    /**
     * Constructor.
     * @param int $communityId
     */
    public function __construct(int $communityId = 0)
    {
        // Get the row from the database
        $communityRow = DB::table('communities')
            ->where('id', $communityId)
            ->first();

        // Populate the variables
        if ($communityRow) {
            $this->id = intval($communityRow->id);
            $this->title_id = intval($communityRow->title_id);
            $this->name = $communityRow->name;
            $this->description = $communityRow->description;
            $this->icon = $communityRow->icon;
            $this->banner = $communityRow->banner;
            $this->category = intval($communityRow->category);
            $this->type = intval($communityRow->type);
            $this->created = $communityRow->created;
            $this->platform = intval($communityRow->platform);
            $this->perms = $communityRow->perms;
        } elseif ($communityId !== 0) {
            $this->id = -1;
        }
    }
}
