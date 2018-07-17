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
 * Faculty management table for Middlesex School's Dorm and Student functions plugin.
 *
 * @package    local_mxschool
 * @subpackage user_management
 * @author     Jeremiah DeGreeff, Class of 2019 <jrdegreeff@mxschool.edu>
 * @author     Charles J McDonald, Academic Technology Specialist <cjmcdonald@mxschool.edu>
 * @copyright  2018, Middlesex School, 1400 Lowell Rd, Concord MA
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__.'/../classes/mx_table.php');

class faculty_table extends local_mxschool_table {

    /**
     * Creates a new faculty_table.
     *
     * @param stdClass $filter any filtering for the table - could include dorm or search.
     */
    public function __construct($filter) {
        $columns = array('name', 'dorm', 'approvesignout', 'advisoryavailable', 'advisoryclosing');
        $headers = array();
        foreach ($columns as $column) {
            $headers[] = get_string("faculty_report_header_{$column}", 'local_mxschool');
        }
        $columns[] = 'actions';
        $headers[] = get_string('report_header_actions', 'local_mxschool');
        $fields = array(
            'f.id', "CONCAT(u.lastname, ', ', u.firstname) AS name", 'd.name AS dorm', 'f.may_approve_signout AS approvesignout',
            'f.advisory_available AS advisoryavailable', 'f.advisory_closing AS advisoryclosing'
        );
        $from = array('{local_mxschool_faculty} f', '{user} u ON f.userid = u.id', '{local_mxschool_dorm} d ON f.dormid = d.id');
        $where = array('u.deleted = 0', $filter->dorm ? "d.id = $filter->dorm" : '');
        $sortable = array('name', 'dorm', 'approvesignout', 'advisoryavailable', 'advisoryclosing');
        $urlparams = array('dorm' => $filter->dorm, 'search' => $filter->search);
        $centered = array('approvesignout', 'advisoryavailable', 'advisoryclosing');
        $searchable = array('u.firstname', 'u.lastname');
        parent::__construct(
            'faculty_table', $columns, $headers, $sortable, 'name', $fields, $from, $where, $urlparams, $centered, $filter->search,
            $searchable
        );
    }

    /**
     * Formats the approve signout column.
     */
    protected function col_approvesignout($values) {
        return $values->approvesignout ? get_string('yes') : get_string('no');
    }

    /**
     * Formats the advisory available column.
     */
    protected function col_advisoryavailable($values) {
        return $values->advisoryavailable ? get_string('yes') : get_string('no');
    }

    /**
     * Formats the advisory closing column.
     */
    protected function col_advisoryclosing($values) {
        return $values->advisoryclosing ? get_string('yes') : get_string('no');
    }

    /**
     * Formats the actions column.
     */
    protected function col_actions($values) {
        return $this->edit_icon('/local/mxschool/user_management/faculty_edit.php', $values->id);
    }

}
