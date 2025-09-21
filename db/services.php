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
 * Web service definitions for Progress Analytics block.
 *
 * @package   block_progressanalytics
 * @copyright 2025 Alex
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = array(

    'block_progressanalytics_get_course_quiz_metrics' => array(
        'classname'   => 'block_progressanalytics\external\get_course_quiz_metrics',
        'methodname'  => 'get_course_quiz_metrics',
        'description' => 'Get quiz metrics for the current user in a course',
        'type'        => 'read',
        'ajax'        => true,
        'loginrequired' => true,
        'capabilities' => 'block/progressanalytics:view',
    ),

);

$services = array(
    'Progress Analytics Block Services' => array(
        'functions' => array(
            'block_progressanalytics_get_course_quiz_metrics',
        ),
        'restrictedusers' => 0,
        'enabled' => 1,
    )
);
