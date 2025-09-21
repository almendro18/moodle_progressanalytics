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
 * Block instance edit form for Progress Analytics block.
 *
 * @package   block_progressanalytics
 * @copyright 2025 Alex
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Form for editing Progress Analytics block instances.
 */
class block_progressanalytics_edit_form extends block_edit_form {

    /**
     * Extends the configuration form for Progress Analytics block.
     *
     * @param MoodleQuickForm $mform
     */
    protected function specific_definition($mform) {

        // Section header.
        $mform->addElement('header', 'config_header', get_string('config_title', 'block_progressanalytics'));

        // Block title.
        $mform->addElement('text', 'config_blocktitle', get_string('blocktitle', 'block_progressanalytics'));
        $mform->setDefault('config_blocktitle', get_string('pluginname', 'block_progressanalytics'));
        $mform->setType('config_blocktitle', PARAM_TEXT);
        $mform->addHelpButton('config_blocktitle', 'blocktitle', 'block_progressanalytics');

        // Show progress chart.
        $mform->addElement('selectyesno', 'config_showprogress', get_string('showprogress', 'block_progressanalytics'));
        $mform->setDefault('config_showprogress', 1);
        $mform->addHelpButton('config_showprogress', 'showprogress', 'block_progressanalytics');

        // Show results chart.
        $mform->addElement('selectyesno', 'config_showresults', get_string('showresults', 'block_progressanalytics'));
        $mform->setDefault('config_showresults', 1);
        $mform->addHelpButton('config_showresults', 'showresults', 'block_progressanalytics');

        // Show comparison chart.
        $mform->addElement('selectyesno', 'config_showcomparison', get_string('showcomparison', 'block_progressanalytics'));
        $mform->setDefault('config_showcomparison', 1);
        $mform->addHelpButton('config_showcomparison', 'showcomparison', 'block_progressanalytics');
    }
}
