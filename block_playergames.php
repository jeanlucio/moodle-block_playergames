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
 * PlayerGames Block main class.
 *
 * @package    block_playergames
 * @copyright  2026 Jean Lúcio
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_playergames extends block_base {
    /**
     * Initialize block title.
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_playergames');
    }

    /**
     * Get block content for display.
     *
     * @return stdClass|string
     */
    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        global $USER;

        $this->content = new \stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        if (!isloggedin() || isguestuser()) {
            return $this->content;
        }

        $context = \context_block::instance($this->instance->id);
        if (!has_capability('block/playergames:view', $context)) {
            return $this->content;
        }

        $allowed = get_config('local_playergames', 'allowed_participants') ?: 'students';
        $isstaff = \local_playergames\local\access::is_staff((int) $USER->id);
        $isadmin = has_capability('moodle/site:config', \context_system::instance());

        $widget   = new \block_playergames\output\widget((int) $USER->id, $isstaff, $isadmin, $allowed);
        $renderer = $this->page->get_renderer('core');
        $data     = $widget->export_for_template($renderer);

        if ($data['state'] === 'hidden') {
            return $this->content;
        }

        if ($data['state'] === 'paused') {
            $this->content->text = $renderer->render_from_template('block_playergames/paused', $data);
            return $this->content;
        }

        $this->content->text = $renderer->render_from_template('block_playergames/widget', $data);
        return $this->content;
    }

    /**
     * Allow multiple instances of the block in the same page.
     *
     * @return bool
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Define where this block can be added.
     *
     * @return array
     */
    public function applicable_formats() {
        return [
            'course-view' => true,
            'site' => false,
            'my' => true,
        ];
    }

    /**
     * Enable block configuration.
     *
     * @return bool
     */
    public function has_config() {
        return false;
    }
}
