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
 * Define the steps to restore a codesnippet from a backup.
 *
 * @package    mod_codesnippet
 * @copyright  2019 onwards Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/codesnippet/backup/moodle2/restore_codesnippet_stepslib.php');

/**
 * The codesnippet restore task that provides all the settings and steps to perform activity restore.
 *
 * @package    mod_codesnippet
 * @copyright  2019 onwards Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_codesnippet_activity_task extends restore_activity_task {

    /**
     * Define particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity.
    }

    /**
     * Define particular steps this activity can have.
     */
    protected function define_my_steps() {
        // The codesnippet activity only has one structure step.
        $this->add_step(new restore_codesnippet_activity_structure_step('codesnippet_structure', 'codesnippet.xml'));
    }

    /**
     * Define the contents in the activity that must be processed by the link decoder.
     */
    public static function define_decode_contents() {
        $contents = [];

        $contents[] = new restore_decode_content('codesnippet', array('intro'), 'codesnippet');

        return $contents;
    }

    /**
     * Define the decoding rules for links belonging to the activity to be executed by the link decoder.
     */
    public static function define_decode_rules() {
        return [];
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * codesnippet logs. It must return one array
     * of {@link restore_log_rule} objects
     */
    public static function define_restore_log_rules() {
        $rules = [];

        $rules[] = new restore_log_rule('codesnippet', 'add', 'view.php?id={course_module}', '{codesnippet}');
        $rules[] = new restore_log_rule('codesnippet', 'update', 'view.php?id={course_module}', '{codesnippet}');
        $rules[] = new restore_log_rule('codesnippet', 'view', 'view.php?id={course_module}', '{codesnippet}');

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * course logs. It must return one array
     * of {@link restore_log_rule} objects
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     */
    public static function define_restore_log_rules_for_course() {
        $rules = [];

        $rules[] = new restore_log_rule('codesnippet', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}
