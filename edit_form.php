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
 * Form for editing block my_certificates instances.
 *
 * @package   blocks
 * @subpackage   my_certificates
 * @copyright 2013 Valery Fremaux (valery.fremaux@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_my_certificates_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $DB, $COURSE, $DB;

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('advcheckbox', 'config_accessbehalfed', get_string('accessbehalf', 'block_my_certificates'));
        $mform->addHelpButton('config_accessbehalfed', 'accessbehalf', 'block_my_certificates');

        $mform->addElement('text', 'config_title', get_string('title', 'block_my_certificates'));
        $mform->setDefault('config_title', get_string('pluginname','block_my_certificates'));
        $mform->setType('config_title', PARAM_MULTILANG);

    }

}
