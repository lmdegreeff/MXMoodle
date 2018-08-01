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
 * Page for students to submit a weekend form for Middlesex School's Dorm and Student functions plugin.
 *
 * @package    local_mxschool
 * @subpackage checkin
 * @author     Jeremiah DeGreeff, Class of 2019 <jrdegreeff@mxschool.edu>
 * @author     Charles J McDonald, Academic Technology Specialist <cjmcdonald@mxschool.edu>
 * @copyright  2018, Middlesex School, 1400 Lowell Rd, Concord MA
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../../config.php');
require_once(__DIR__.'/../locallib.php');
require_once(__DIR__.'/../classes/output/renderable.php');
require_once(__DIR__.'/../classes/mx_notifications.php');
require_once('weekend_form.php');

require_login();
$isstudent = user_is_student();
if (!$isstudent) {
    require_capability('local/mxschool:manage_weekend', context_system::instance());
}

$id = optional_param('id', 0, PARAM_INT);

$parents = array(
    get_string('pluginname', 'local_mxschool') => '/local/mxschool/index.php',
    get_string('checkin', 'local_mxschool') => '/local/mxschool/checkin/index.php'
);
$redirect = get_redirect($parents);
$url = '/local/mxschool/checkin/weekend_enter.php';
$title = get_string('weekend_form', 'local_mxschool');

setup_mxschool_page($url, $title, $parents);

$queryfields = array('local_mxschool_weekend_form' => array('abbreviation' => 'wf', 'fields' => array(
    'id', 'userid' => 'student', 'weekendid' => 'weekend', 'departure_date_time' => 'departure_date',
    'return_date_time' => 'return_date', 'destination', 'transportation', 'phone_number' => 'phone',
    'time_created' => 'timecreated', 'time_modified' => 'timemodified'
)));

if ($isstudent && !student_may_access_weekend($USER->id)) {
    redirect($redirect);
}
if ($id) {
    if (!$DB->record_exists('local_mxschool_weekend_form', array('id' => $id))) {
        redirect($redirect);
    }
    if ($isstudent) { // Students cannot edit existing weekend forms.
        redirect(new moodle_url($url));
    }
    $data = get_record($queryfields, "wf.id = ?", array($id));
    $data->dorm = $DB->get_field('local_mxschool_student', 'dormid', array('userid' => $data->student));
} else {
    $data = new stdClass();
    $data->id = $id;
    $data->timecreated = time();
    $data->departure_date = time();
    $data->return_date = time();
    if ($isstudent) {
        $data->student = $USER->id;
        $record = $DB->get_record_sql(
            "SELECT CONCAT(u.lastname, ', ', u.firstname) AS student, u.firstname, u.alternatename, d.id AS dorm,
                    CONCAT(hoh.firstname, ' ', hoh.lastname) AS hoh, d.permissions_line AS permissionsline
             FROM {local_mxschool_student} s
             LEFT JOIN {user} u ON s.userid = u.id
             LEFT JOIN {local_mxschool_dorm} d ON s.dormid = d.id
             LEFT JOIN {user} hoh ON d.hohid = hoh.id
             WHERE s.userid = ?", array($USER->id)
        );
        $record->student = $record->student.(
            $record->alternatename && $record->alternatename !== $record->firstname ? " ({$record->alternatename})" : ''
        );
        $data->dorm = $record->dorm;
    } else {
        $dorm = $DB->get_field('local_mxschool_faculty', 'dormid', array('userid' => $USER->id));
        if ($dorm) {
            $data->dorm = $dorm;
        }
    }
}
$data->isstudent = $isstudent ? '1' : '0';
$data->warning = get_config('local_mxschool', 'weekend_form_warning_closed');
generate_time_selector_fields($data, 'departure', 15);
generate_time_selector_fields($data, 'return', 15);
$dorms = array('0' => get_string('report_select_boarding_dorm', 'local_mxschool')) + get_boarding_dorm_list();
$students = get_boarding_student_list();

$form = new weekend_form(array('id' => $id, 'dorms' => $dorms, 'students' => $students));
$form->set_redirect($redirect);
$form->set_data($data);

if ($form->is_cancelled()) {
    redirect($form->get_redirect());
} else if ($data = $form->get_data()) {
    $data->timemodified = time();
    $data->departure_date = generate_timestamp($data, 'departure');
    $data->return_date = generate_timestamp($data, 'return');
    $departurestartbound = new DateTime('now', core_date::get_server_timezone_object());
    $departurestartbound->setTimestamp($data->departure_date);
    $departureendbound = clone $departurestartbound;
    $departurestartbound->modify('+4 days'); // Map 0:00:00 Wednesday to 0:00:00 Sunday.
    $departureendbound->modify('-3 days'); // Map 0:00:00 Tuesday to 0:00:00 Sunday.
    $data->weekend = $DB->get_field_sql(
        "SELECT id FROM {local_mxschool_weekend} WHERE ? >= sunday_time AND ? < sunday_time",
        array($departurestartbound->getTimestamp(), $departureendbound->getTimestamp())
    );
    $id = update_record($queryfields, $data);
    $oldrecord = $DB->get_record_sql(
        "SELECT * FROM {local_mxschool_weekend_form} WHERE userid = ? AND weekendid = ? AND id <> ? AND active = 1",
        array($data->student, $data->weekend, $id)
    );
    if ($oldrecord) {
        $oldrecord->active = 0; // Each student can have only one active record for a given weekend.
        $DB->update_record('local_mxschool_weekend_form', $oldrecord);
    }
    $result = mx_notifications::send_email('weekend_form_submitted', array('id' => $id));
    logged_redirect(
        $form->get_redirect(), get_string('weekend_form_success', 'local_mxschool'), $data->id ? 'update' : 'create'
    );
}

$output = $PAGE->get_renderer('local_mxschool');
$bottominstructions = get_config('local_mxschool', 'weekend_form_instructions_bottom');
$bottominstructions = str_replace(
    '{hoh}', $isstudent ? $record->hoh : get_string(
        'weekend_form_instructions_placeholder_hoh', 'local_mxschool'
    ), $bottominstructions
);
$bottominstructions = str_replace(
    '{permissionsline}', $isstudent ? $record->permissionsline : get_string(
        'weekend_form_instructions_placeholder_permissionsline', 'local_mxschool'
    ), $bottominstructions
);
$formrenderable = new \local_mxschool\output\form(
    $form, get_config('local_mxschool', 'weekend_form_instructions_top'), $bottominstructions
);
$jsrenderable = new \local_mxschool\output\amd_module('local_mxschool/weekend_form');

echo $output->header();
echo $output->heading($title.($isstudent ? " for {$record->student} &ndash; {$dorms[$record->dorm]}" : ''));
echo $output->render($formrenderable);
echo $output->render($jsrenderable);
echo $output->footer();
