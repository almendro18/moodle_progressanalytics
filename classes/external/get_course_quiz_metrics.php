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
 * External function for retrieving course quiz metrics.
 *
 * @package   block_progressanalytics
 * @copyright 2025 Alex
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_progressanalytics\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_multiple_structure;
use core_external\external_value;
use context_course;
use cache;

defined('MOODLE_INTERNAL') || die();

/**
 * External API class that provides course quiz metrics for the block.
 */
class get_course_quiz_metrics extends external_api {

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function get_course_quiz_metrics_parameters() {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
            )
        );
    }

    /**
     * Get quiz metrics for the current user in a course
     *
     * @param int $courseid Course ID
     * @return array Quiz metrics data
     */
    public static function get_course_quiz_metrics($courseid) {
        global $USER, $DB, $CFG;

        // Load required libraries
        require_once($CFG->libdir . '/gradelib.php');
        require_once($CFG->dirroot . '/mod/quiz/locallib.php');
        require_once($CFG->libdir . '/completionlib.php');

        $params = self::validate_parameters(self::get_course_quiz_metrics_parameters(), array(
            'courseid' => $courseid
        ));

        $course = $DB->get_record('course', array('id' => $params['courseid']), '*', MUST_EXIST);
        $context = context_course::instance($course->id);

        require_login($course);
        self::validate_context($context);

        require_capability('block/progressanalytics:view', $context);

        // Check cache first.
        $cache = cache::make('block_progressanalytics', 'usermetrics');
        $cachekey = $USER->id . '_' . $courseid;
        $cacheddata = $cache->get($cachekey);
        
        if ($cacheddata !== false) {
            return $cacheddata;
        }

        // Get all quizzes in the course (for results/comparison).
        $modinfo = get_fast_modinfo($course);
        $quizzes = array();
        
        foreach ($modinfo->get_instances_of('quiz') as $quiz) {
            if ($quiz->uservisible) {
                $quizzes[] = $quiz;
            }
        }

        // Initialize result structure.
        $result = array(
            'progress' => array(
                'completed' => 0,
                'total' => 0,
                'percentage' => 0
            ),
            'results' => array(),
            'comparison' => array(
                'studentMean' => 0,
                'courseMean' => 0,
                'percentile' => 0,
                'hasComparison' => false
            )
        );

        // Compute progress using selected activity module types (not completion settings).
        $includehidden = (int) get_config('block_progressanalytics', 'includehidden');
        $configuredmods = get_config('block_progressanalytics', 'progressmodules');
        $allowedmods = array('quiz', 'assign');
        if (!empty($configuredmods)) {
            if (is_array($configuredmods)) {
                $allowedmods = $configuredmods;
            } else if (is_string($configuredmods)) {
                // Stored as comma-separated list.
                $allowedmods = array_filter(array_map('trim', explode(',', $configuredmods)));
            }
        }

        $completedactivities = 0;
        $totalactivities = 0;

        foreach ($modinfo->get_cms() as $cm) {
            if (!$includehidden && !$cm->uservisible) {
                continue;
            }
            if (!in_array($cm->modname, $allowedmods, true)) {
                continue;
            }
            $totalactivities++;

            switch ($cm->modname) {
                case 'quiz':
                    $qattempts = quiz_get_user_attempts($cm->instance, $USER->id, 'finished', true);
                    if (!empty($qattempts)) {
                        $completedactivities++;
                    }
                    break;
                case 'assign':
                    // Consider completed if there's a submitted submission or a non-null grade.
                    $submitted = $DB->record_exists('assign_submission', array(
                        'assignment' => $cm->instance,
                        'userid' => $USER->id,
                        'status' => 'submitted'
                    ));
                    if ($submitted) {
                        $completedactivities++;
                        break;
                    }
                    $grade = $DB->get_record('assign_grades', array(
                        'assignment' => $cm->instance,
                        'userid' => $USER->id
                    ), 'id,grade');
                    if ($grade && $grade->grade !== null) {
                        $completedactivities++;
                    }
                    break;
                default:
                    // For other allowed modules (if configured), count as not completed (unknown criteria).
                    break;
            }
        }

        $result['progress']['completed'] = $completedactivities;
        $result['progress']['total'] = $totalactivities;
        $result['progress']['percentage'] = $totalactivities > 0 ? round(($completedactivities / $totalactivities) * 100, 1) : 0;

        // Get user's quiz attempts and grades (for results).
        $userquizgrades = array();
        $completedquizzes = 0;
        
        foreach ($quizzes as $quiz) {
            // Check if user has attempts.
            $attempts = quiz_get_user_attempts($quiz->instance, $USER->id, 'finished', true);
            
            if (!empty($attempts)) {
                $completedquizzes++;
                
                // Get grade from gradebook.
                $grades = grade_get_grades($courseid, 'mod', 'quiz', $quiz->instance, $USER->id);
                
                if (!empty($grades->items) && !empty($grades->items[0]->grades[$USER->id])) {
                    $grade = $grades->items[0]->grades[$USER->id];
                    $item  = $grades->items[0];
                    
                    if ($grade->grade !== null) {
                        // Use grademin/grademax from the grade item, not from the user grade object.
                        $normalizedgrade = self::normalize_grade($grade->grade, $item->grademin, $item->grademax);
                        $userquizgrades[] = array(
                            'quizid' => $quiz->instance,
                            'name' => $quiz->name,
                            'grade' => round($normalizedgrade, 1),
                            'date' => end($attempts)->timefinish
                        );
                    }
                } else {
                    // Try to get the grade directly from attempts
                    $lastattempt = end($attempts);
                    
                    // Get the quiz record from database to get sumgrades
                    $quizrecord = $DB->get_record('quiz', array('id' => $quiz->instance));
                    
                    if ($quizrecord && $lastattempt->sumgrades !== null && $quizrecord->sumgrades > 0) {
                        $attemptgrade = ($lastattempt->sumgrades / $quizrecord->sumgrades) * 100;
                        $userquizgrades[] = array(
                            'quizid' => $quiz->instance,
                            'name' => $quiz->name,
                            'grade' => round($attemptgrade, 1),
                            'date' => $lastattempt->timefinish
                        );
                    } else {
                        // If no grade available, add with 0 grade for tracking completion
                        $userquizgrades[] = array(
                            'quizid' => $quiz->instance,
                            'name' => $quiz->name,
                            'grade' => 0,
                            'date' => $lastattempt->timefinish
                        );
                    }
                }
            }
        }

        // Progress already calculated using completion-enabled activities.

        // Sort results by date.
        usort($userquizgrades, function($a, $b) {
            return $a['date'] - $b['date'];
        });
        $result['results'] = $userquizgrades;

        // Fallback: if no allowed modules were found, use quizzes to compute progress so the block remains informative.
        if (empty($result['progress']['total'])) {
            $result['progress']['total'] = count($quizzes);
            $result['progress']['completed'] = $completedquizzes;
            $result['progress']['percentage'] = count($quizzes) > 0 ? round(($completedquizzes / count($quizzes)) * 100, 1) : 0;
        }

        // Calculate student mean.
        if (!empty($userquizgrades)) {
            $total = array_sum(array_column($userquizgrades, 'grade'));
            $result['comparison']['studentMean'] = round($total / count($userquizgrades), 1);
        }

        // Get course comparison data (cached separately).
        $coursecache = cache::make('block_progressanalytics', 'coursemetrics');
        $coursecachekey = $courseid;
        $coursedata = $coursecache->get($coursecachekey);

        if ($coursedata === false) {
            $coursedata = self::calculate_course_metrics($course, $quizzes);
            $coursecache->set($coursecachekey, $coursedata);
        }

        // Show comparison data if we have at least 2 participants (including current user)
        if ($coursedata['participantcount'] >= 2 && !empty($userquizgrades)) {
            $result['comparison']['courseMean'] = $coursedata['coursemean'];
            $result['comparison']['percentile'] = self::calculate_percentile(
                $result['comparison']['studentMean'], 
                $coursedata['allgrades']
            );
            $result['comparison']['hasComparison'] = true;
        } else if ($coursedata['participantcount'] > 0) {
            $result['comparison']['courseMean'] = $coursedata['coursemean'];
            // Show comparison even with fewer participants, just don't calculate percentile
            $result['comparison']['hasComparison'] = true;
        }

        // Cache for 5 minutes.
        $cache->set($cachekey, $result);
        
        return $result;
    }

    /**
     * Calculate course-wide metrics
     */
    private static function calculate_course_metrics($course, $quizzes) {
        global $DB;
        
        // Get enrolled students (users with student-like capabilities).
        $context = context_course::instance($course->id);
        $students = get_enrolled_users($context, 'mod/quiz:attempt', 0, 'u.id', null, 0, 0, true);
        
        $allgrades = array();
        $participantgrades = array();
        
        foreach ($students as $student) {
            $studentquizgrades = array();
            
            foreach ($quizzes as $quiz) {
                $attempts = quiz_get_user_attempts($quiz->instance, $student->id, 'finished', true);
                
                if (!empty($attempts)) {
                    $grades = grade_get_grades($course->id, 'mod', 'quiz', $quiz->instance, $student->id);
                    if (!empty($grades->items) && !empty($grades->items[0]->grades[$student->id])) {
                        $grade = $grades->items[0]->grades[$student->id];
                        $item  = $grades->items[0];
                        if ($grade->grade !== null) {
                            // Use grademin/grademax from the grade item, not from the user grade object.
                            $normalizedgrade = self::normalize_grade($grade->grade, $item->grademin, $item->grademax);
                            $studentquizgrades[] = $normalizedgrade;
                        }
                    } else {
                        // Try to get grade directly from attempts as fallback
                        $lastattempt = end($attempts);
                        $quizrecord = $DB->get_record('quiz', array('id' => $quiz->instance));
                        
                        if ($quizrecord && $lastattempt->sumgrades !== null && $quizrecord->sumgrades > 0) {
                            $attemptgrade = ($lastattempt->sumgrades / $quizrecord->sumgrades) * 100;
                            $studentquizgrades[] = $attemptgrade;
                        } else {
                            // Include 0 grades to maintain data consistency
                            $studentquizgrades[] = 0;
                        }
                    }
                }
            }
            
            if (!empty($studentquizgrades)) {
                $studentmean = array_sum($studentquizgrades) / count($studentquizgrades);
                $allgrades[] = $studentmean;
                $participantgrades[$student->id] = $studentmean;
            }
        }
        
        $coursemean = !empty($allgrades) ? round(array_sum($allgrades) / count($allgrades), 1) : 0;
        
        return array(
            'coursemean' => $coursemean,
            'allgrades' => $allgrades,
            'participantcount' => count($allgrades)
        );
    }

    /**
     * Normalize grade to 0-100 scale
     */
    private static function normalize_grade($grade, $grademin, $grademax) {
        if ($grademax == $grademin) {
            return 0;
        }
        return (($grade - $grademin) / ($grademax - $grademin)) * 100;
    }

    /**
     * Calculate percentile
     */
    private static function calculate_percentile($value, $array) {
        if (empty($array)) {
            return 0;
        }
        
        $count = count($array);
        $below = 0;
        
        foreach ($array as $v) {
            if ($v < $value) {
                $below++;
            }
        }
        
        return round(($below / $count) * 100);
    }

    /**
     * Returns description of method result value
     *
     * @return external_single_structure
     */
    public static function get_course_quiz_metrics_returns() {
        return new external_single_structure(
            array(
                'progress' => new external_single_structure(
                    array(
                        'completed' => new external_value(PARAM_INT, 'Number of completed quizzes'),
                        'total' => new external_value(PARAM_INT, 'Total number of quizzes'),
                        'percentage' => new external_value(PARAM_FLOAT, 'Completion percentage'),
                    )
                ),
                'results' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'quizid' => new external_value(PARAM_INT, 'Quiz ID'),
                            'name' => new external_value(PARAM_TEXT, 'Quiz name'),
                            'grade' => new external_value(PARAM_FLOAT, 'Normalized grade (0-100)'),
                            'date' => new external_value(PARAM_INT, 'Completion timestamp'),
                        )
                    ), 'User quiz results', VALUE_OPTIONAL
                ),
                'comparison' => new external_single_structure(
                    array(
                        'studentMean' => new external_value(PARAM_FLOAT, 'Student average grade'),
                        'courseMean' => new external_value(PARAM_FLOAT, 'Course average grade'),
                        'percentile' => new external_value(PARAM_INT, 'Student percentile'),
                        'hasComparison' => new external_value(PARAM_BOOL, 'Whether comparison data is available'),
                    )
                ),
            )
        );
    }
}
