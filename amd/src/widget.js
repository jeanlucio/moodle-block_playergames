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
 * Hoists the history/help button row into the block's own title row, so they
 * sit on the same line as "PlayerGames" instead of on a row of their own.
 * Mirrors block_playerhud's view.js.
 */
const hoistHeaderActions = () => {
    const widget = document.querySelector('.bpg-widget');
    if (!widget) {
        return;
    }
    const btnRow = widget.querySelector('.bpg-header-actions');
    const block = widget.closest('.block_playergames');
    if (!btnRow || !block) {
        return;
    }
    const titleEl = block.querySelector('.card-title');
    if (!titleEl) {
        return;
    }

    const wrapper = document.createElement('div');
    wrapper.className = 'd-flex align-items-center mb-2';
    titleEl.parentElement.insertBefore(wrapper, titleEl);
    titleEl.classList.add('mb-0');
    wrapper.appendChild(titleEl);

    btnRow.remove();
    btnRow.classList.remove('mb-2');
    btnRow.classList.add('ms-auto');
    wrapper.appendChild(btnRow);
};

/**
 * Initialises the widget's interactions.
 *
 * The ranking toggles reload the page on change (the default), matching the
 * Hub page's own behaviour — the widget's "your position" line needs to
 * appear/disappear immediately when the user opts in or out, and a reload is
 * the simplest way to reflect that reliably.
 */
const init = () => {
    hoistHeaderActions();
    AvatarModal.init();
    HelpModal.init();
    RankingToggle.wire('[data-ranking-visibility]', 'local_playergames_set_ranking_visibility');
    RankingToggle.wire('[data-learning-ranking-visibility]', 'local_playergames_set_learning_ranking_visibility');
};

export {init};
