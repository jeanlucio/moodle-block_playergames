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
 * Tests for the PlayerGames block privacy provider.
 *
 * @package    block_playergames
 * @copyright  2026 Jean Lúcio
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_playergames\privacy;

use PHPUnit\Framework\Attributes\CoversClass;
use core_privacy\local\metadata\null_provider;

/**
 * Unit tests for {@see provider}.
 *
 * @package    block_playergames
 * @copyright  2026 Jean Lúcio
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
#[CoversClass(provider::class)]
final class provider_test extends \advanced_testcase {
    public function test_implements_null_provider(): void {
        $this->assertInstanceOf(null_provider::class, new provider());
    }

    public function test_get_reason_points_to_the_metadata_string(): void {
        $this->assertSame('privacy:metadata', provider::get_reason());
        $this->assertNotEmpty(get_string(provider::get_reason(), 'block_playergames'));
    }
}
