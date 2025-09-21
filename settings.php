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
 * Admin settings definition for Progress Analytics block.
 *
 * @package   block_progressanalytics
 * @copyright 2025 Alex
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    
    // Include hidden quizzes setting.
    $settings->add(new admin_setting_configcheckbox(
        'block_progressanalytics/includehidden',
        get_string('config_includehidden', 'block_progressanalytics'),
        get_string('config_includehidden_desc', 'block_progressanalytics'),
        0
    ));

    // Cache interval setting.
    $settings->add(new admin_setting_configselect(
        'block_progressanalytics/cacheinterval',
        get_string('config_cacheinterval', 'block_progressanalytics'),
        get_string('config_cacheinterval_desc', 'block_progressanalytics'),
        5,
        array(
            1 => get_string('config_minutes_1', 'block_progressanalytics'),
            2 => get_string('config_minutes_2', 'block_progressanalytics'),
            5 => get_string('config_minutes_5', 'block_progressanalytics'),
            10 => get_string('config_minutes_10', 'block_progressanalytics'),
            15 => get_string('config_minutes_15', 'block_progressanalytics'),
            30 => get_string('config_minutes_30', 'block_progressanalytics'),
            60 => get_string('config_minutes_60', 'block_progressanalytics')
        )
    ));

    // Minimum participants for comparison.
    $settings->add(new admin_setting_configselect(
        'block_progressanalytics/minparticipants',
        get_string('config_minparticipants', 'block_progressanalytics'),
        get_string('config_minparticipants_desc', 'block_progressanalytics'),
        5,
        array(
            3 => get_string('config_participants_3', 'block_progressanalytics'),
            5 => get_string('config_participants_5', 'block_progressanalytics'),
            10 => get_string('config_participants_10', 'block_progressanalytics'),
            15 => get_string('config_participants_15', 'block_progressanalytics'),
            20 => get_string('config_participants_20', 'block_progressanalytics')
        )
    ));

    // Activity modules to include in progress calculation.
    $modoptions = [];
    foreach (core_component::get_plugin_list('mod') as $modname => $path) {
        // Provide a short, friendly label when available.
        $label = get_string('modulename', 'mod_' . $modname);
        $modoptions[$modname] = $label;
    }
    // Provide sane defaults if labels are not available.
    if (empty($modoptions)) {
        $modoptions = [
            'quiz' => get_string('defaultmod_quiz', 'block_progressanalytics'),
            'assign' => get_string('defaultmod_assign', 'block_progressanalytics'),
        ];
    }

    $settings->add(new admin_setting_configmultiselect(
        'block_progressanalytics/progressmodules',
        get_string('config_progressmodules', 'block_progressanalytics'),
        get_string('config_progressmodules_desc', 'block_progressanalytics'),
        ['quiz', 'assign'],
        $modoptions
    ));

    // Chart type for results.
    $settings->add(new admin_setting_configselect(
        'block_progressanalytics/charttype',
        get_string('config_charttype', 'block_progressanalytics'),
        get_string('config_charttype_desc', 'block_progressanalytics'),
        'line',
        array(
            'line' => get_string('config_charttype_line', 'block_progressanalytics'),
            'bar' => get_string('config_charttype_bar', 'block_progressanalytics')
        )
    ));

    // Limit number of results shown by default.
    $settings->add(new admin_setting_configtext(
        'block_progressanalytics/resultslimit',
        get_string('config_resultslimit', 'block_progressanalytics'),
        get_string('config_resultslimit_desc', 'block_progressanalytics'),
        4,
        PARAM_INT
    ));

    // Show percentile information.
    $settings->add(new admin_setting_configcheckbox(
        'block_progressanalytics/showpercentile',
        get_string('config_showpercentile', 'block_progressanalytics'),
        get_string('config_showpercentile_desc', 'block_progressanalytics'),
        1
    ));
}
