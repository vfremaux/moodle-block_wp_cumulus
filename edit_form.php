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
 * Form for editing HTML block instances.
 *
 * @package   block_wp_cumulus
 * @copyright 2012 Valery Fremaux (valery.fremaux@gmail.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @version   Moodle 2.x
 */

class block_wp_cumulus_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
    	global $COURSE;
    	
        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('configtitle', 'block_wp_cumulus'));
        $mform->setType('config_title', PARAM_MULTILANG);

        $mform->addElement('text', 'config_width', get_string('configwidth', 'block_wp_cumulus'));
        $mform->setType('config_width', PARAM_INT);

        $mform->addElement('text', 'config_height', get_string('configheight', 'block_wp_cumulus'));
        $mform->setType('config_height', PARAM_INT);

        $mform->addElement('text', 'config_tcolor', get_string('configtcolor', 'block_wp_cumulus'));
        $mform->setType('config_tcolor', PARAM_INT);

        $mform->addElement('text', 'config_bgcolor', get_string('configbgcolor', 'block_wp_cumulus'));
        $mform->setType('config_bgcolor', PARAM_INT);

		$yesnooptions[0] = get_string('no');
		$yesnooptions[1] = get_string('yes');
        $mform->addElement('select', 'config_trans', get_string('configtrans', 'block_wp_cumulus'), $yesnooptions);
        $mform->setType('config_trans', PARAM_BOOL);
        $mform->setHelpButton('config_trans', 'configtrans', 'block_wp_cumulus');

		$speedoptions[100] = 100;
		$speedoptions[75] = 75;
		$speedoptions[50] = 50;
		$speedoptions[25] = 25;
        $mform->addElement('select', 'config_speed', get_string('configspeed', 'block_wp_cumulus'), $speedoptions);
        $mform->setType('config_speed', PARAM_INT);
        $mform->setHelpButton('config_speed', 'configspeed', 'block_wp_cumulus');

        $mform->addElement('select', 'config_distribution', get_string('configdistribution', 'block_wp_cumulus'), $yesnooptions);
        $mform->setType('config_distribution', PARAM_BOOL);
        $mform->setHelpButton('config_distribution', 'configdistribution', 'block_wp_cumulus');

		$displayoptions['tags'] = get_string('tags', 'block_wp_cumulus');
		$displayoptions['cats'] = get_string('cats', 'block_wp_cumulus');
		$displayoptions['both'] = get_string('both', 'block_wp_cumulus');
        $mform->addElement('select', 'config_display', get_string('configdisplay', 'block_wp_cumulus'), $displayoptions);
        $mform->setType('config_display', PARAM_ALPHA);

       	$mform->addElement('text', 'config_args', get_string('configargs', 'block_wp_cumulus'));
        $mform->setType('config_args', PARAM_TEXT);
        $mform->setAdvanced('config_args');
        $mform->setHelpButton('config_args', 'configargs', 'block_wp_cumulus');

        $mform->addElement('select', 'config_compmode', get_string('configcompmode', 'block_wp_cumulus'), $yesnooptions);
        $mform->setType('config_compmode', PARAM_BOOL);
        $mform->setAdvanced('config_compmode');
        $mform->setHelpButton('config_compmode', 'configcompmode', 'block_wp_cumulus');

        $mform->addElement('select', 'config_showwptags', get_string('configshowwptags', 'block_wp_cumulus'), $yesnooptions);
        $mform->setType('config_showwptags', PARAM_BOOL);
        $mform->setAdvanced('config_showwptags');
        $mform->setHelpButton('config_showwptags', 'configshowwptags', 'block_wp_cumulus');

        $mform->addElement('textarea', 'config_tagcloud', get_string('configtagcloud', 'block_wp_cumulus'), $array('cols' => 50, 'rows' => 10));
        $mform->setType('config_tagcloud', PARAM_TEXT);
        $mform->setHelpButton('config_tagcloud', 'configtagcloud', 'block_wp_cumulus');

    }

    function set_data($defaults, &$files = null) {
    	global $COURSE;
    	
        if (!$this->block->user_can_edit() && !empty($this->block->config->title)) {
            // If a title has been set but the user cannot edit it format it nicely
            $title = $this->block->config->title;
            $defaults->config_title = format_string($title, true, $this->page->context);
            // Remove the title from the config so that parent::set_data doesn't set it.
            unset($this->block->config->title);
        }

        parent::set_data($defaults);

        if (isset($title)) {
            // Reset the preserved title
            $this->block->config->title = $title;
        }

    }
}
