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

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $options = array();

    $defaultplugin = 'certificate';
    if ($DB->record_exists('modules', array('name' => 'certificate'))) {
        $options['certificate'] = get_string('pluginname', 'certificate');
    }
    if ($DB->record_exists('modules', array('name' => 'pdcertificate'))) {
        $options['pdcertificate'] = get_string('pluginname', 'pdcertificate');
        $defaultplugin = 'pdcertificate';
    }

    $settings->add(new admin_setting_configselect('block_my_certificates/certificateplugin', get_string('configplugin', 'block_my_certificates'),
                       get_string('configplugin_desc', 'block_my_certificates'), $defaultplugin, $options));

}