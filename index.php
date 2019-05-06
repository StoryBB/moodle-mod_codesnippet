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
 * Code snippet
 *
 * @package    mod_codesnippet
 * @copyright  2019 onwards Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

$id = optional_param('id', 0, PARAM_INT); // Course module id.

if (!$id) {
    print_error('invalidcoursemodule');
}

$PAGE->set_url('/mod/codesnippet/index.php', ['id' => $id]);
if (!$cm = get_coursemodule_from_id('codesnippet', $id)) {
    print_error('invalidcoursemodule');
}

if (!$course = $DB->get_record('course', ['id' => $cm->course])) {
    print_error('coursemisconf');
}

if (!$codesnippet = $DB->get_record('codesnippet', ['id' => $cm->instance])) {
    print_error('invalidcoursemodule');
}

require_login($course, true, $cm);

// Find where we need to redirect to by asking the course format.
require_once($CFG->dirroot . '/course/format/lib.php');
$format = course_get_format($course);

$url = $format->get_view_url($cm->section);
$url->set_anchor('module-' . $id);

redirect($url);
