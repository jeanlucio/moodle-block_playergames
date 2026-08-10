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

    // Core/block.mustache already renders the title and the edit controls
    // (move/kebab, editing mode only) as siblings inside one flex row, with
    // the controls pushed right via ms-auto. Reuse that same row instead of
    // building a nested one: an inner wrapper only grows to fit its own
    // content, so an ms-auto inside it just hugs the title instead of
    // reaching the card's right edge. Insert the buttons right after the
    // title and move ms-auto onto them, so both button groups end up
    // clustered together at the right edge.
    const editControls = block.querySelector('.block-controls');
    if (editControls) {
        editControls.classList.remove('ms-auto');
        editControls.classList.add('ms-2');
    }
    btnRow.classList.remove('mb-2');
    btnRow.classList.add('ms-auto');
    titleEl.insertAdjacentElement('afterend', btnRow);
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
