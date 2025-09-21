<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Event observers for Progress Analytics block.
 *
 * @package   block_progressanalytics
 * @copyright 2025 Alex
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_progressanalytics;

use cache;

defined('MOODLE_INTERNAL') || die();

/**
 * Handles Moodle events to keep block caches in sync.
 */
class observer {

    /**
     * Observer for quiz attempt submitted event
     * @param \mod_quiz\event\attempt_submitted $event
     */
    public static function quiz_attempt_submitted(\mod_quiz\event\attempt_submitted $event) {
        self::invalidate_user_cache($event->userid, $event->courseid);
    }

    /**
     * Observer for quiz attempt finished event
     * @param \mod_quiz\event\attempt_finished $event
     */
    public static function quiz_attempt_finished(\mod_quiz\event\attempt_finished $event) {
        self::invalidate_user_cache($event->userid, $event->courseid);
    }

    /**
     * Observer for quiz grade updated event
     * @param \mod_quiz\event\quiz_grade_updated $event
     */
    public static function quiz_grade_updated(\mod_quiz\event\quiz_grade_updated $event) {
        self::invalidate_user_cache($event->relateduserid, $event->courseid);
    }

    /**
     * Observer for course module completion updated event
     * @param \core\event\course_module_completion_updated $event
     */
    public static function course_module_completion_updated(\core\event\course_module_completion_updated $event) {
        // Only handle quiz module completions
        if ($event->other['modulename'] === 'quiz') {
            self::invalidate_user_cache($event->userid, $event->courseid);
        }
    }

    /**
     * Invalidate user cache for analytics
     * @param int $userid User ID
     * @param int $courseid Course ID
     */
    private static function invalidate_user_cache($userid, $courseid) {
        $cache = cache::make('block_progressanalytics', 'usermetrics');
        $cachekey = $userid . '_' . $courseid;
        $cache->delete($cachekey);

        // Also invalidate course metrics cache as it may need to be recalculated
        $coursecache = cache::make('block_progressanalytics', 'coursemetrics');
        $coursecachekey = $courseid;
        $coursecache->delete($coursecachekey);
    }
}
