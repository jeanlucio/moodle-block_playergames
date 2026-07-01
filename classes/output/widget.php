<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Sidebar widget renderable for the PlayerGames block.
 *
 * @package    block_playergames
 * @copyright  2026 Jean Lúcio
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_playergames\output;

use core_user;
use local_playergames\hub\avatar_manager;
use local_playergames\hub\checkin_manager;
use local_playergames\hub\daily_play_manager;
use local_playergames\hub\learning_xp_manager;
use local_playergames\hub\level_manager;
use local_playergames\hub\season_manager;
use local_playergames\hub\streak_manager;
use local_playergames\hub\xp_manager;
use local_playergames\local\access;
use local_playergames\local\preferences;
use moodle_url;
use renderable;
use renderer_base;
use stdClass;
use templatable;
use user_picture;

/**
 * A thin shell over local_playergames's managers — no business logic of its
 * own beyond picking which of three states to render.
 *
 * @package    block_playergames
 * @copyright  2026 Jean Lúcio
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class widget implements renderable, templatable {
    /** @var int Current user ID. */
    private int $userid;

    /** @var bool True when access::is_staff() returns true for this user. */
    private bool $isstaff;

    /** @var bool True when user has moodle/site:config. */
    private bool $isadmin;

    /** @var string Value of the local_playergames allowed_participants setting. */
    private string $allowed;

    /**
     * Constructs the widget renderable.
     *
     * @param int    $userid  Current user ID.
     * @param bool   $isstaff True when access::is_staff() returns true for this user.
     * @param bool   $isadmin True when user has moodle/site:config.
     * @param string $allowed Value of the local_playergames allowed_participants setting.
     */
    public function __construct(int $userid, bool $isstaff, bool $isadmin, string $allowed) {
        $this->userid  = $userid;
        $this->isstaff = $isstaff;
        $this->isadmin = $isadmin;
        $this->allowed = $allowed;
    }

    /**
     * Picks the widget's state (normal, paused or hidden) and exports its data.
     *
     * @param renderer_base $output Moodle renderer.
     * @return array
     */
    public function export_for_template(renderer_base $output): array {
        if (!preferences::is_gamification_enabled($this->userid)) {
            return $this->export_paused();
        }

        $season = season_manager::get_active();
        if (!$season) {
            return ['state' => 'hidden'];
        }

        if (!access::can_view_hub($this->isstaff, $this->isadmin, $this->allowed)) {
            return ['state' => 'hidden'];
        }

        return $this->export_normal($output, $season);
    }

    /**
     * Exports the paused (gamification opted out) state.
     *
     * @return array
     */
    private function export_paused(): array {
        return [
            'state'          => 'paused',
            'str_paused'     => get_string('widget_paused', 'block_playergames'),
            'str_reactivate' => get_string('widget_reactivate', 'block_playergames'),
            'url_reactivate' => (new moodle_url('/local/playergames/gamification_preferences.php'))->out(false),
        ];
    }

    /**
     * Exports the normal (active player) state.
     *
     * @param renderer_base $output Moodle renderer, used to resolve the user picture URL.
     * @param stdClass $season The active season.
     * @return array
     */
    private function export_normal(renderer_base $output, stdClass $season): array {
        global $PAGE;

        $staffids = access::get_staff_ids();

        $profile   = xp_manager::get_or_create_profile($this->userid, $season->id);
        $streak    = streak_manager::get_or_create($this->userid);
        $level     = xp_manager::get_level($profile->xp);
        $leveldata = xp_manager::build_level_data($profile->xp, $level);
        $snapshot  = season_manager::get_config_snapshot($season);
        $games     = daily_play_manager::get_games_today($this->userid, $snapshot);

        $checkindaily = (int) ($snapshot['xp_checkin_daily'] ?? 5);
        $checkincap   = (int) ($snapshot['xp_cap_checkin_season'] ?? 150);
        $checkinmax   = $checkindaily > 0 ? intdiv($checkincap, $checkindaily) : 0;
        $checkindone  = checkin_manager::count_this_season($this->userid, $season, $checkinmax);

        $rankingsetting = get_config('local_playergames', 'enable_ranking');
        $rankingenabled = ($rankingsetting === false) ? true : (bool) $rankingsetting;

        $selfposition = 0;
        if ($rankingenabled && (bool) $profile->showinranking) {
            $selfposition = xp_manager::get_season_position(
                $this->userid,
                $season->id,
                (int) $profile->xp,
                (int) $profile->timemodified,
                $staffids,
                $this->isstaff
            );
        }

        $showlearningxp = learning_xp_manager::is_visible_to($this->allowed);
        $learningxp             = 0;
        $learningshowinranking  = false;
        $learningxprankingenabled = false;
        $learningposition       = 0;
        if ($showlearningxp) {
            $learningcache            = learning_xp_manager::get_or_create_cache($this->userid);
            $learningxp               = (int) $learningcache->windowedxp;
            $learningshowinranking    = (bool) $learningcache->showinranking;
            $learningxprankingenabled = (bool) get_config('local_playergames', 'learningxpranking');
            if ($learningxprankingenabled && $learningshowinranking && $learningxp > 0) {
                $learningposition = learning_xp_manager::get_position(
                    $this->userid,
                    $learningxp,
                    (int) $learningcache->timemodified
                );
            }
        }

        $equippedavatar = avatar_manager::get_equipped($this->userid);
        $avatarsbytier  = [];
        foreach (avatar_manager::get_collection($this->userid) as $avatar) {
            $avatar['equipvalue'] = $avatar['equipped'] ? '' : $avatar['emoji'];
            $tier = $avatar['tier'];
            if (!isset($avatarsbytier[$tier])) {
                $avatarsbytier[$tier] = [
                    'requiredlevel' => $avatar['requiredlevel'],
                    'avatars'       => [],
                ];
            }
            $avatarsbytier[$tier]['avatars'][] = $avatar;
        }
        $avatarsbytier = array_values($avatarsbytier);

        $user           = core_user::get_user($this->userid);
        $userpicture    = new user_picture($user);
        $userpicture->size = 100;
        $userpictureurl = $userpicture->get_url($PAGE)->out(false);

        return [
            'state'              => 'normal',
            'username'           => fullname($user),
            'url_history'        => (new moodle_url('/local/playergames/history.php'))->out(false),
            'url_hub'            => (new moodle_url('/local/playergames/hub.php'))->out(false),
            'str_open_hub'       => get_string('widget_open_hub', 'block_playergames'),
            'str_games'          => get_string('hub_games_section', 'local_playergames'),

            'equippedavatar'     => $equippedavatar,
            'hasequippedavatar'  => $equippedavatar !== '',
            'userpictureurl'     => $userpictureurl,
            'avatarsbytier'      => $avatarsbytier,
            'str_avatars'        => get_string('hub_avatars_section', 'local_playergames'),
            'str_avatar_change'  => get_string('hub_avatar_change', 'local_playergames'),
            'str_avatar_locked'  => get_string('hub_avatar_locked', 'local_playergames'),
            'str_avatars_hint'   => get_string('hub_avatars_hint', 'local_playergames'),

            'str_help_modal_title'          => get_string('help_modal_title', 'local_playergames'),
            'str_help_trigger'              => get_string('help_trigger', 'local_playergames'),
            'str_section_season_xp_title'   => get_string('help_section_season_xp_title', 'local_playergames'),
            'str_section_season_xp_body'    => get_string('help_section_season_xp_body', 'local_playergames'),
            'str_section_learning_xp_title' => get_string('help_section_learning_xp_title', 'local_playergames'),
            'str_section_learning_xp_body'  => get_string('help_section_learning_xp_body', 'local_playergames'),
            'str_section_avatars_title'     => get_string('help_section_avatars_title', 'local_playergames'),
            'str_section_avatars_body'      => get_string('help_section_avatars_body', 'local_playergames'),
            'str_section_rankings_title'    => get_string('help_section_rankings_title', 'local_playergames'),
            'str_section_rankings_body'     => get_string('help_section_rankings_body', 'local_playergames'),

            'level'              => $level,
            'xp'                 => (int) $profile->xp,
            'xp_next'            => $leveldata['xp_next'],
            'progress_pct'       => $leveldata['progress_pct'],
            'maxlevel'           => $level >= level_manager::max_level(),
            'str_level'          => get_string('hub_level', 'local_playergames'),
            'str_xp'             => get_string('hub_xp', 'local_playergames'),
            'str_xp_next'        => get_string('hub_xp_next_level', 'local_playergames'),

            'games'              => $games,

            'streak'             => (int) $streak->currentstreak,
            'freezes'            => (int) $streak->freezesavailable,
            'str_streak'         => get_string('hub_streak', 'local_playergames'),
            'str_freezes'        => get_string('hub_freezes', 'local_playergames'),
            'hascheckin'         => $checkinmax > 0,
            'checkin_done'       => $checkindone,
            'checkin_max'        => $checkinmax,
            'str_checkins'       => get_string('hub_checkins', 'local_playergames'),

            'showlearningxp'     => $showlearningxp,
            'learningxp'         => $learningxp,
            'str_learningxp'     => get_string('hub_learningxp', 'local_playergames'),

            'rankingenabled'          => $rankingenabled,
            'showinranking'           => (bool) $profile->showinranking,
            'hasposition'             => $selfposition > 0,
            'str_position'            => $selfposition > 0
                ? get_string('hub_your_position', 'local_playergames', $selfposition)
                : '',
            'str_showinranking'        => get_string('hub_showinranking', 'local_playergames'),
            'learningxprankingenabled' => $learningxprankingenabled,
            'learningshowinranking'    => $learningshowinranking,
            'haslearningposition'      => $learningposition > 0,
            'str_learning_position'    => $learningposition > 0
                ? get_string('hub_your_position', 'local_playergames', $learningposition)
                : '',
            'str_show_learningxpranking' => get_string('hub_show_learningxpranking', 'local_playergames'),
        ];
    }
}
