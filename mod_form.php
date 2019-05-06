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
 * Add codesnippet form
 *
 * @package    mod_codesnippet
 * @copyright  2019 onwards Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Defines the configuration options form for adding/updating this activity.
 *
 *
 * @package    mod_codesnippet
 * @copyright  2019 onwards Peter Spicer <peter.spicer@catalyst-eu.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_codesnippet_mod_form extends moodleform_mod {

    /**
     * Defines the options being added to the form.
     */
    public function definition() {
        global $PAGE;

        $PAGE->force_settings_menu();

        $mform = $this->_form;

        $mform->addElement('header', 'generalhdr', get_string('general'));
        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('codesnippetname', 'codesnippet'), ['size' => '64']);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('textarea', 'intro', get_string('modulename', 'codesnippet'), 'rows="20" cols="80"');
        $mform->setType('intro', PARAM_RAW);

        $mform->addElement('header', 'snippetopts', get_string('snippetoptions', 'codesnippet'));

        $mform->addElement('select', 'language', get_string('snippetlanguage', 'codesnippet') , $this->get_languages());
        $mform->setDefault('language', 'plaintext');
        $mform->addElement('advcheckbox', 'linenumbers', get_string('showlinenumbers', 'codesnippet'));

        // Label does not add "Show description" checkbox meaning that 'intro' is always shown on the course page.
        $mform->addElement('hidden', 'showdescription', 1);
        $mform->setType('showdescription', PARAM_INT);

        $this->standard_coursemodule_elements();

        $this->add_action_buttons(true, false, null);
    }

    /**
     * Returns a list of all the languages currently supported for highlighting.
     *
     * @return array Array of hljs class-name -> language name choices for highlighting.
     */
    protected function get_languages(): array {
        // This doesn't use translations because you don't really translate the language names.
        return [
            'plaintext' => get_string('plaintext', 'codesnippet'),
            'apache' => 'Apache',
            'bash' => 'Bash',
            'csharp' => 'C#',
            'css' => 'CSS',
            'coffeescript' => 'CoffeeScript',
            'diff' => 'Diff',
            'gherkin' => 'Gherkin',
            'handlebars' => 'Handlebars',
            'html' => 'HTML, XML',
            'http' => 'HTTP',
            'ini' => 'INI',
            'json' => 'JSON',
            'java' => 'Java',
            'javascript' => 'JavaScript',
            'makefile' => 'Makefile',
            'markdown' => 'Markdown',
            'nginx' => 'nginx',
            'objectivec' => 'Objective-C',
            'php' => 'PHP',
            'properties' => 'Properties',
            'python' => 'Python',
            'ruby' => 'Ruby',
            'scss' => 'SCSS',
            'sql' => 'SQL',
            'shell' => 'Shell',
        ];
    }
}
