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
 * On-campus signout report for Middlesex's eSignout Subplugin.
 *
 * @package     local_signout
 * @subpackage  on_campus
 * @author      Jeremiah DeGreeff, Class of 2019 <jrdegreeff@mxschool.edu>
 * @author      Charles J McDonald, Academic Technology Specialist <cjmcdonald@mxschool.edu>
 * @copyright   2019 Middlesex School, 1400 Lowell Rd, Concord MA 01742 All Rights Reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../../config.php');
require_once(__DIR__.'/../locallib.php');

require_login();
require_capability('local/signout:manage_on_campus', context_system::instance());

$filter = new stdClass();
$filter->dorm = get_param_faculty_dorm();
$filter->location = optional_param('location', 0, PARAM_INT);
$filter->date = get_param_current_date_on_campus();
$filter->search = optional_param('search', '', PARAM_RAW);
$action = optional_param('action', '', PARAM_RAW);
$id = optional_param('id', 0, PARAM_INT);

setup_mxschool_page('report', 'on_campus', 'signout');
$refresh = get_config('local_signout', 'on_campus_refresh_rate');
if ($refresh) {
    $PAGE->set_url(new moodle_url($PAGE->url, (array) $filter));
    $PAGE->set_periodic_refresh_delay((int) $refresh);
}

$locations = get_on_campus_location_list() + array(-1 => get_string('on_campus:report:select_location:other', 'local_signout'));
if ($filter->location && !isset($locations[$filter->location])) { // Invalid location.
    unset($filter->location);
    redirect(new moodle_url($PAGE->url, (array) $filter));
}
if ($action === 'delete' && $id) {
    $result = $DB->record_exists('local_signout_on_campus', array('id' => $id)) ? 'success' : 'failure';
    $DB->set_field('local_signout_on_campus', 'deleted', 1, array('id' => $id));
    logged_redirect(
        new moodle_url($PAGE->url, (array) $filter), get_string("on_campus:form:delete:{$result}", 'local_signout'), 'delete',
        $result === 'success'
    );
}

$dates = get_on_campus_date_list();

$table = new local_signout\local\on_campus\table($filter);
$dropdowns = array(
    local_mxschool\output\dropdown::dorm_dropdown($filter->dorm),
    new local_mxschool\output\dropdown(
        'location', $locations, $filter->location, get_string('on_campus:report:select_location:all', 'local_signout')
    ),
    new local_mxschool\output\dropdown(
        'date', $dates, $filter->date, get_string('on_campus:report:select_date:all', 'local_signout')
    )
);
$buttons = array(new local_mxschool\output\redirect_button(
    get_string('on_campus:report:add', 'local_signout'), new moodle_url('/local/signout/on_campus/form.php')
));

$output = $PAGE->get_renderer('local_signout');
$renderable = new local_mxschool\output\report($table, $filter->search, $dropdowns, $buttons);

echo $output->header();
echo $output->heading(
    get_string('on_campus:report:title', 'local_signout', $filter->dorm > 0 ? format_dorm_name($filter->dorm) . ' ' : '')
);
echo $output->render($renderable);
echo $output->footer();
