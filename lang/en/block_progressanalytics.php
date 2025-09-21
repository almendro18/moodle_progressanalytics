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
 * English language strings for Progress Analytics block.
 *
 * @package   block_progressanalytics
 * @copyright 2025 Alex
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Progress Analytics';
$string['progressanalytics:addinstance'] = 'Add a new progress analytics block';
$string['progressanalytics:myaddinstance'] = 'Add a new progress analytics block to Dashboard';
$string['progressanalytics:view'] = 'View progress analytics';
$string['progressanalytics:viewall'] = 'View extended progress analytics';

// UI Strings
$string['loading'] = 'Loading analytics...';
$string['errorloadingdata'] = 'Error loading analytics data. Please try again later.';
$string['noquizzes'] = 'No activities configured for progress in this course.';
$string['progress'] = 'Activities Progress';
$string['results'] = 'My Results';
$string['comparison'] = 'Course Comparison';

// Chart Labels
$string['progresschartlabel'] = 'Progress chart showing completed activities percentage';
$string['resultschartlabel'] = 'Results chart showing grades per quiz';
$string['comparisonchartlabel'] = 'Comparison chart showing student vs course average';

// Privacy
$string['privacy:metadata'] = 'The Progress Analytics block does not store personal data. It only displays aggregated information from existing gradebook and quiz attempt data.';
$string['privacy:metadata:core_cache'] = 'The Progress Analytics block caches quiz analytics data to improve performance. This cache contains computed metrics but no additional personal information.';

// Settings
$string['config_title'] = 'Progress Analytics Configuration';
$string['config_includehidden'] = 'Include hidden quizzes';
$string['config_includehidden_desc'] = 'Include hidden quizzes in analytics calculations';
$string['config_cacheinterval'] = 'Cache interval (minutes)';
$string['config_cacheinterval_desc'] = 'How long to cache analytics data (1-60 minutes)';
$string['config_minparticipants'] = 'Minimum participants for comparison';
$string['config_minparticipants_desc'] = 'Minimum number of participants needed to show course comparison (3-20)';
$string['config_charttype'] = 'Results chart type';
$string['config_charttype_desc'] = 'Choose the chart type for displaying quiz results';
$string['config_charttype_line'] = 'Line chart';
$string['config_charttype_bar'] = 'Bar chart';
$string['config_showpercentile'] = 'Show percentile information';
$string['config_showpercentile_desc'] = 'Display student percentile in course comparison';
$string['config_progressmodules'] = 'Activity types to include in progress';
$string['config_progressmodules_desc'] = 'Select which course activity types are included in progress calculation (e.g., Quizzes, Assignments). Completion settings are not used.';
$string['config_resultslimit'] = 'Max results in "My Results"';
$string['config_resultslimit_desc'] = 'Number of quizzes to show by default (a button will reveal all)';

$string['config_minutes_1'] = '1 minute';
$string['config_minutes_2'] = '2 minutes';
$string['config_minutes_5'] = '5 minutes';
$string['config_minutes_10'] = '10 minutes';
$string['config_minutes_15'] = '15 minutes';
$string['config_minutes_30'] = '30 minutes';
$string['config_minutes_60'] = '60 minutes';
$string['config_participants_3'] = '3 participants';
$string['config_participants_5'] = '5 participants';
$string['config_participants_10'] = '10 participants';
$string['config_participants_15'] = '15 participants';
$string['config_participants_20'] = '20 participants';
$string['defaultmod_quiz'] = 'Quizzes';
$string['defaultmod_assign'] = 'Assignments';

// Block Instance Settings
$string['blocktitle'] = 'Block title';
$string['blocktitle_desc'] = 'Custom title for this block instance';
$string['showprogress'] = 'Show progress chart';
$string['showprogress_desc'] = 'Display the quiz completion progress chart';
$string['showresults'] = 'Show results chart';
$string['showresults_desc'] = 'Display the quiz results chart';
$string['showcomparison'] = 'Show comparison chart';
$string['showcomparison_desc'] = 'Display the course comparison chart';

// UI actions
$string['showall'] = 'Show all';
$string['showless'] = 'Show less';

// JS/localized labels
$string['js_notstarted'] = 'Not started';
$string['js_completed'] = 'Completed';
$string['js_noresults'] = 'Complete quizzes to see your results here';
$string['js_noresults_desc'] = 'You have not completed any quizzes yet';
$string['js_personalavg'] = 'Personal average';
$string['js_quizzes'] = 'quizzes';
$string['js_myaverage'] = 'My average';
$string['js_courseaverage'] = 'Course average';
$string['js_comparison_pending'] = 'Comparison data will appear when more students complete quizzes';
$string['js_courseavgprefix'] = 'Course average:';
$string['js_yourpercentileprefix'] = 'Your percentile:';
$string['progress_tooltip_completed'] = 'Completed: {count}';
$string['progress_tooltip_remaining'] = 'Remaining: {count}';
$string['progress_summary'] = '{completed} of {total} activities completed';
$string['results_empty_message'] = 'No quiz results available yet';
$string['results_dataset_label'] = 'Grade';
$string['results_tooltip'] = 'Grade: {grade}%';
$string['results_summary'] = 'Personal average: {average}% ({count} quizzes)';
$string['comparison_tooltip'] = '{label}: {value}%';
$string['comparison_summary_with_percentile'] = 'Course average: {course}% • Your percentile: {percentile}%';
$string['comparison_summary_without_percentile'] = 'Course average: {course}%';

// Cache definitions
$string['cachedef_usermetrics'] = 'Cached per-user progress data';
$string['cachedef_coursemetrics'] = 'Cached course-wide analytics data';
