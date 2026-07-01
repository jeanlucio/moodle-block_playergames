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
 * PlayerGames widget AMD module — wires the avatar modal, help modal and
 * ranking visibility toggles, all reused from local_playergames.
 *
 * @module     block_playergames/widget
 * @copyright  2026 Jean Lúcio
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import * as AvatarModal from 'local_playergames/avatar_modal';
import * as HelpModal from 'local_playergames/help_modal';
import * as RankingToggle from 'local_playergames/ranking_toggle';

/**
 * Initialises the widget's interactions.
 *
 * The ranking toggles pass reload = false: the widget has no ranking list of
 * its own to refresh, unlike the Hub page.
 */
const init = () => {
    AvatarModal.init();
    HelpModal.init();
    RankingToggle.wire('[data-ranking-visibility]', 'local_playergames_set_ranking_visibility', false);
    RankingToggle.wire(
        '[data-learning-ranking-visibility]',
        'local_playergames_set_learning_ranking_visibility',
        false
    );
};

export {init};
