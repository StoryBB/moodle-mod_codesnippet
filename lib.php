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
 * Library of functions and constants for module codesnippet
 *
 * @package    mod_codesnippet
 * @copyright  2019 onwards Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

use core_completion\api;

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param stdClass $codesnippet
 * @return bool|int
 */
function codesnippet_add_instance($codesnippet) {
    global $DB;

    $codesnippet->timemodified = time();

    $id = $DB->insert_record("codesnippet", $codesnippet);

    $completiontimeexpected = !empty($codesnippet->completionexpected) ? $codesnippet->completionexpected : null;
    api::update_completion_date_event($codesnippet->coursemodule, 'codesnippet', $id, $completiontimeexpected);

    return $id;
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param stdClass $codesnippet
 * @return bool
 */
function codesnippet_update_instance($codesnippet) {
    global $DB;

    $codesnippet->timemodified = time();
    $codesnippet->id = $codesnippet->instance;

    $completiontimeexpected = !empty($codesnippet->completionexpected) ? $codesnippet->completionexpected : null;
    api::update_completion_date_event($codesnippet->coursemodule, 'codesnippet', $codesnippet->id, $completiontimeexpected);

    return $DB->update_record("codesnippet", $codesnippet);
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id
 * @return bool
 */
function codesnippet_delete_instance($id) {
    global $DB;

    if (!$codesnippet = $DB->get_record("codesnippet", ["id" => $id])) {
        return false;
    }

    $result = true;

    $cm = get_coursemodule_from_instance('codesnippet', $id);
    api::update_completion_date_event($cm->id, 'codesnippet', $codesnippet->id, null);

    if (!$DB->delete_records("codesnippet", ["id" => $codesnippet->id])) {
        $result = false;
    }

    return $result;
}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 * See get_array_of_activities() in course/lib.php
 *
 * @param stdClass $cm
 * @return cached_cm_info|null
 */
function codesnippet_get_coursemodule_info($cm) {
    global $DB;

    $columns = 'id, name, intro, introformat, language, linenumbers';
    if ($codesnippet = $DB->get_record('codesnippet', ['id' => $cm->instance], $columns)) {
        $info = new cached_cm_info();

        // The name isn't used on the course page because NO_VIEW_LINK is set, but it is elsewhere.
        $info->name = $codesnippet->name;

        // Build the structure we need - no real point templating this as it's pretty fixed structurally.
        $info->content = html_writer::start_tag('pre');
        $class = $codesnippet->language;
        $class .= $codesnippet->linenumbers ? ' hl-line-numbers' : '';
        $info->content .= html_writer::start_tag('code', ['class' => $class]);
        // Normally this would be format_text but this is code, we want to treat it as cleanly as possible.
        $info->content .= htmlspecialchars($codesnippet->intro, ENT_QUOTES, 'UTF-8');
        $info->content .= html_writer::end_tag('code');
        $info->content .= html_writer::end_tag('pre');

        return $info;
    } else {
        return null;
    }
}

/**
 * When rendering a codesnippet block in course, make sure we add our Highlight.js
 * library to it - but enforce once per page just in case.
 *
 * @param cm_info $cm The course module being rendered
 */
function codesnippet_cm_info_dynamic(cm_info $cm) {
    global $PAGE;
    static $loadedhljs = false;
    static $loadedhljsln = false;

    if (!$loadedhljs) {
        $PAGE->requires->js_call_amd('mod_codesnippet/highlight', 'initHighlighting');
        $loadedhljs = true;
    }
    if (!$loadedhljsln) {
        $PAGE->requires->js_call_amd('mod_codesnippet/highlightjs-line-numbers', 'init');
    }
}

/**
 * Describes which features this activity module supports.
 *
 * @uses FEATURE_IDNUMBER
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @uses FEATURE_MOD_ARCHETYPE
 * @uses FEATURE_BACKUP_MOODLE2
 * @uses FEATURE_NO_VIEW_LINK
 * @param string $feature FEATURE_xx constant for requested feature
 * @return bool|null True if module supports feature, false if not, null if doesn't know
 */
function codesnippet_supports($feature) {
    $supports = [
        FEATURE_IDNUMBER => true,
        FEATURE_GROUPS => false,
        FEATURE_GROUPINGS => false,
        FEATURE_MOD_INTRO => false,
        FEATURE_COMPLETION_TRACKS_VIEWS => false,
        FEATURE_GRADE_HAS_GRADE => false,
        FEATURE_GRADE_OUTCOMES => false,
        FEATURE_MOD_ARCHETYPE => MOD_ARCHETYPE_RESOURCE,
        FEATURE_BACKUP_MOODLE2 => true,
        FEATURE_NO_VIEW_LINK => true,
    ];
    return isset($supports[$feature]) ? $supports[$feature] : null;
}
