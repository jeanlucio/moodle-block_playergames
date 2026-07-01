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
 * Step definitions for block_playergames Behat tests.
 *
 * @package    block_playergames
 * @category   test
 * @copyright  2026 Jean Lúcio
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// phpcs:disable moodle.Files.RequireLogin.Missing

use local_playergames\hub\season_manager;
use local_playergames\local\preferences;

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

/**
 * Custom Behat step definitions for the PlayerGames block.
 */
class behat_block_playergames extends behat_base {
    /**
     * Creates and activates a season so the widget renders its normal state
     * instead of "hidden".
     *
     * @Given an active PlayerGames season exists
     */
    public function an_active_playergames_season_exists(): void {
        $season = season_manager::create('Behat season', time() - DAYSECS, time() + DAYSECS);
        season_manager::activate((int) $season->id);
    }

    /**
     * Programmatically disables gamification for a user.
     *
     * Sets the user preference directly so scenarios testing the paused
     * state do not need to run the full opt-out form flow as a prerequisite.
     *
     * @param string $username Moodle username.
     * @Given :username has disabled gamification
     */
    public function user_has_disabled_gamification(string $username): void {
        global $DB;
        $user = $DB->get_record('user', ['username' => $username], '*', MUST_EXIST);
        preferences::set((int) $user->id, false);
    }
}
