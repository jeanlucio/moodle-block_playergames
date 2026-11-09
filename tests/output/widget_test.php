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
 * Tests for the PlayerGames widget renderable.
 *
 * @package    block_playergames
 * @copyright  2026 Jean Lúcio
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_playergames\output;

use local_playergames\hub\daily_play_manager;
use local_playergames\hub\learning_xp_manager;
use local_playergames\hub\xp_manager;
use local_playergames\local\preferences;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * Unit tests for {@see widget}.
 *
 * The only business logic that belongs to this plugin is which of the three
 * states (normal, paused, hidden) export_for_template() picks — everything
 * else is orchestration of local_playergames managers already tested there.
 *
 * @package    block_playergames
 * @copyright  2026 Jean Lúcio
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
#[CoversClass(widget::class)]
final class widget_test extends \advanced_testcase {
    /**
     * Inserts an active season carrying the given config snapshot.
     *
     * @param array $snapshot Config snapshot values.
     * @return int Season id.
     */
    private function make_active_season(array $snapshot = []): int {
        global $DB;
        $now = time();
        return (int) $DB->insert_record('local_playergames_seasons', (object) [
            'name' => 'Season',
            'startdate' => $now - DAYSECS,
            'enddate' => $now + (DAYSECS * 30),
            'status' => 'active',
            'config_snapshot' => json_encode($snapshot),
            'timecreated' => $now,
            'timemodified' => $now,
        ]);
    }

    /**
     * Returns a renderer usable for export_for_template() in a CLI/test context.
     *
     * @return \renderer_base
     */
    private function get_renderer(): \renderer_base {
        global $PAGE;
        return $PAGE->get_renderer('core');
    }

    public function test_state_is_paused_when_gamification_disabled(): void {
        $this->resetAfterTest();
        $user = $this->getDataGenerator()->create_user();
        set_user_preference(preferences::PREF_GAMIFICATION, 0, $user->id);

        $data = (new widget((int) $user->id, false, false, 'students'))->export_for_template($this->get_renderer());

        $this->assertSame('paused', $data['state']);
    }

    public function test_state_is_hidden_when_no_active_season(): void {
        $this->resetAfterTest();
        $user = $this->getDataGenerator()->create_user();

        $data = (new widget((int) $user->id, false, false, 'students'))->export_for_template($this->get_renderer());

        $this->assertSame('hidden', $data['state']);
    }

    public function test_state_is_hidden_when_staff_excluded_by_students_only(): void {
        $this->resetAfterTest();
        $this->make_active_season();
        $user = $this->getDataGenerator()->create_user();

        $data = (new widget((int) $user->id, true, false, 'students'))->export_for_template($this->get_renderer());

        $this->assertSame('hidden', $data['state']);
    }

    public function test_state_is_normal_when_season_active_and_allowed(): void {
        $this->resetAfterTest();
        $this->make_active_season();
        $user = $this->getDataGenerator()->create_user();

        $data = (new widget((int) $user->id, false, false, 'students'))->export_for_template($this->get_renderer());

        $this->assertSame('normal', $data['state']);
    }

    public function test_normal_state_includes_username_and_level(): void {
        $this->resetAfterTest();
        $this->make_active_season();
        $user = $this->getDataGenerator()->create_user(['firstname' => 'Jean', 'lastname' => 'Lucio']);

        $data = (new widget((int) $user->id, false, false, 'students'))->export_for_template($this->get_renderer());

        $this->assertSame('Jean Lucio', $data['username']);
        $this->assertSame(1, $data['level']);
        $this->assertSame(0, $data['xp']);
    }

    public function test_normal_state_includes_gamification_pause_link(): void {
        $this->resetAfterTest();
        $this->make_active_season();
        $user = $this->getDataGenerator()->create_user();

        $data = (new widget((int) $user->id, false, false, 'students'))->export_for_template($this->get_renderer());

        $this->assertStringContainsString(
            '/local/playergames/gamification_preferences.php',
            $data['url_gamification_prefs']
        );
        $this->assertSame(get_string('widget_pause_gamification', 'block_playergames'), $data['str_pause_gamification']);
    }

    public function test_normal_state_hides_learning_xp_when_not_visible(): void {
        $this->resetAfterTest();
        $this->make_active_season();
        set_config('showlearningxp', 0, 'local_playergames');
        $user = $this->getDataGenerator()->create_user();

        $data = (new widget((int) $user->id, false, false, 'students'))->export_for_template($this->get_renderer());

        $this->assertFalse($data['showlearningxp']);
    }

    public function test_normal_state_shows_self_position_when_opted_into_ranking(): void {
        $this->resetAfterTest();
        $seasonid = $this->make_active_season();
        $user = $this->getDataGenerator()->create_user();
        xp_manager::set_ranking_visibility((int) $user->id, $seasonid, true);

        $data = (new widget((int) $user->id, false, false, 'students'))->export_for_template($this->get_renderer());

        $this->assertTrue($data['showinranking']);
        $this->assertTrue($data['hasposition']);
        $this->assertNotSame('', $data['str_position']);
        $this->assertSame(get_string('widget_deactivate', 'block_playergames'), $data['str_ranking_toggle']);
    }

    public function test_normal_state_shows_learning_position_when_opted_into_ranking(): void {
        $this->resetAfterTest();
        $this->make_active_season();
        set_config('showlearningxp', 1, 'local_playergames');
        set_config('learningxpranking', 1, 'local_playergames');
        $user = $this->getDataGenerator()->create_user();
        learning_xp_manager::record_change((int) $user->id, 50);
        learning_xp_manager::set_ranking_visibility((int) $user->id, true);

        $data = (new widget((int) $user->id, false, false, 'students'))->export_for_template($this->get_renderer());

        $this->assertSame(50, $data['learningxp']);
        $this->assertTrue($data['learningxprankingenabled']);
        $this->assertTrue($data['learningshowinranking']);
        $this->assertTrue($data['haslearningposition']);
        $this->assertNotSame('', $data['str_learning_position']);
        $this->assertSame(
            get_string('widget_deactivate', 'block_playergames'),
            $data['str_learning_ranking_toggle']
        );
    }

    /**
     * Regression test: a shortcut to a game that cannot be played today is not a link.
     *
     * The hub leaves the URL empty for a game already played, and the template wrote it as href="",
     * a link that only reloaded the page.
     */
    public function test_game_shortcuts_link_only_when_the_game_can_be_played(): void {
        global $DB;
        $this->resetAfterTest();
        $this->redirectEvents();
        $seasonid = $this->make_active_season(['xp_per_game_battle' => 10, 'xp_games_battle' => 1]);
        $now = time();
        $DB->insert_record('local_playergames_cartridges', (object) [
            'name' => 'C', 'version' => '1.0', 'language' => 'en', 'type' => 'quiz',
            'timecreated' => $now, 'timemodified' => $now, 'uploadedby' => 0, 'active' => 1,
        ]);
        $user     = $this->getDataGenerator()->create_user();
        $renderer = $this->get_renderer();
        $render   = fn(): string => $renderer->render_from_template(
            'block_playergames/widget',
            (new widget((int) $user->id, false, false, 'students'))->export_for_template($renderer)
        );

        $html = $render();
        $this->assertStringContainsString('/local/playergames/play_battle.php', $html);
        $this->assertStringNotContainsString('href=""', $html);

        daily_play_manager::register_play(
            (int) $user->id,
            'battle',
            mktime(0, 0, 0),
            $DB->get_record('local_playergames_seasons', ['id' => $seasonid], '*', MUST_EXIST),
            \context_system::instance()
        );
        $html = $render();
        $this->assertStringNotContainsString('play_battle.php', $html);
        $this->assertStringNotContainsString('href=""', $html);
        $this->assertStringContainsString('/local/playergames/play_quiz.php', $html);
    }
}
