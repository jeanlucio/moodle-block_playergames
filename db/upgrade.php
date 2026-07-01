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
 * Upgrade steps for block_playergames.
 *
 * @package    block_playergames
 * @copyright  2026 Jean Lúcio
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Runs upgrade steps between plugin versions.
 *
 * @param int $oldversion Previous installed version.
 * @return bool
 */
function xmldb_block_playergames_upgrade(int $oldversion): bool {
    if ($oldversion < 2026070101) {
        // Editing db/access.php only affects fresh installs; sites that
        // already installed the block keep the original archetype list.
        // Grant view to every authenticated user, since the widget shows on
        // the site front page and Dashboard, where a user's course-level
        // roles never reach (same reasoning already applied to
        // local/playergames:viewhub).
        $systemcontext = context_system::instance();

        foreach (get_archetype_roles('user') as $role) {
            assign_capability(
                'block/playergames:view',
                CAP_ALLOW,
                $role->id,
                $systemcontext->id,
                true
            );
        }

        $systemcontext->mark_dirty();

        upgrade_plugin_savepoint(true, 2026070101, 'block', 'playergames');
    }

    return true;
}
