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
 * Main block definition for Progress Analytics block.
 *
 * @package   block_progressanalytics
 * @copyright 2025 Alex
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block class implementing the Progress Analytics block.
 */
class block_progressanalytics extends block_base {

    /**
     * Initializes the block
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_progressanalytics');
    }

    /**
     * Which page types this block may appear on
     *
     * @return array
     */
    public function applicable_formats() {
        return array(
            'course-view' => true,
            'course-view-social' => true,
            'course-view-topics' => true,
            'course-view-weeks' => true,
            'mod' => false,
            'my' => false,
            'site' => false
        );
    }

    /**
     * Whether the block has a settings.php file
     *
     * @return bool
     */
    public function has_config() {
        return true;
    }

    /**
     * Allow multiple instances per page
     *
     * @return bool
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * Allow configuration
     *
     * @return bool
     */
    public function instance_allow_config() {
        return true;
    }

    /**
     * Set the applicable contexts
     *
     * @return array
     */
    public function applicable_contexts() {
        return array(CONTEXT_COURSE);
    }

    /**
     * Default return is false - header and footer are shown
     * @return boolean
     */
    public function hide_header() {
        return false;
    }

    /**
     * Default placement within the page
     * @return array
     */
    public function preferred_width() {
        return 100;
    }

    /**
     * Gets the block contents
     *
     * @return stdClass
     */
    public function get_content() {
        global $USER, $COURSE, $PAGE, $OUTPUT, $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        // Check if user is in a course context and has permission.
        if ($COURSE->id == 1) { // Site course ID is always 1.
            return $this->content;
        }

        $context = context_course::instance($COURSE->id);
        if (!has_capability('block/progressanalytics:view', $context)) {
            return $this->content;
        }

        // Prepare template context.
        $limit = (int) get_config('block_progressanalytics', 'resultslimit');
        if ($limit <= 0) {
            $limit = 4;
        }
        $templatecontext = array(
            'courseid' => $COURSE->id,
            'userid' => $USER->id,
            'sesskey' => sesskey(),
            'wwwroot' => $CFG->wwwroot,
            'blockid' => $this->instance->id,
            'resultslimit' => $limit,
            'showall' => get_string('showall', 'block_progressanalytics'),
            'showless' => get_string('showless', 'block_progressanalytics')
        );

        // Render template.
        $this->content->text = $OUTPUT->render_from_template('block_progressanalytics/main', $templatecontext);

        // Add JavaScript module.
        $PAGE->requires->js_call_amd('block_progressanalytics/charts', 'init', array($COURSE->id));

        return $this->content;
    }

    /**
     * Serialize and store config data
     */
    public function instance_config_save($data, $nolongerused = false) {
        $config = clone($data);
        // Remove any summernote text/format pairs.
        if (!empty($config->text) && is_array($config->text)) {
            $config->text = $config->text['text'];
        }
        parent::instance_config_save($config, $nolongerused);
    }
}
