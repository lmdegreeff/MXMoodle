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
 * Edit page for off-campus signout type records for Middlesex's eSignout Subplugin.
 *
 * @package     local_signout
 * @subpackage  off_campus
 * @author      Jeremiah DeGreeff, Class of 2019 <jrdegreeff@mxschool.edu>
 * @author      Charles J McDonald, Academic Technology Specialist <cjmcdonald@mxschool.edu>
 * @copyright   2019 Middlesex School, 1400 Lowell Rd, Concord MA 01742 All Rights Reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../../config.php');
require_once(__DIR__.'/../locallib.php');

require_login();
require_capability('local/signout:manage_off_campus_preferences', context_system::instance());

$id = optional_param('id', 0, PARAM_INT);

setup_edit_page('type_edit', 'preferences', 'off_campus', 'signout');

$queryfields = array(
    'local_signout_type' => array(
        'abbreviation' => 't',
        'fields' => array(
            'id', 'required_permissions' => 'permissions', 'name', 'grade', 'boarding_status', 'weekend_only' => 'weekend',
            'enabled', 'start_date' => 'start', 'end_date' => 'end', 'form_warning', 'email_warning'
        )
    )
);

if ($id) { // Updating an existing record.
    if (!$DB->record_exists('local_signout_type', array('id' => $id, 'deleted' => 0))) {
        redirect_to_fallback();
    }
    $data = get_record($queryfields, 't.id = ?', array($id));
    $data->form_warning = array('text' => $data->form_warning);
    $data->email_warning = array('text' => $data->email_warning);
} else { // Creating a new record.
    $data = new stdClass();
    $data->id = $id;
    $data->weekend = '-1'; // Invalid default to prevent auto selection.
    $data->enabled = '-1'; // Invalid default to prevent auto selection.
}

$form = new local_signout\local\off_campus\type_edit_form();
$form->set_data($data);

if ($form->is_cancelled()) {
    redirect($form->get_redirect());
} else if ($data = $form->get_data()) {
    if (!$data->permissions) {
        unset($data->permissions);
    }
    if (!$data->start) {
        unset($data->start);
    }
    if (!$data->end) {
        unset($data->end);
    }
    if ($data->form_warning['text']) {
        $data->form_warning = $data->form_warning['text'];
    } else {
        unset($data->form_warning);
    }
    if ($data->email_warning['text']) {
        $data->email_warning = $data->email_warning['text'];
    } else {
        unset($data->email_warning);
    }
    update_record($queryfields, $data);
    $action = $data->id ? 'update' : 'create';
    logged_redirect($form->get_redirect(), get_string("off_campus:type:{$action}:success", 'local_signout'), $action);
}

$output = $PAGE->get_renderer('local_signout');
$renderable = new local_mxschool\output\form($form);

echo $output->header();
echo $output->heading($PAGE->title);
echo $output->render($renderable);
echo $output->footer();
