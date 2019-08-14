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
 * Form for editing parent data for Middlesex's Dorm and Student Functions Plugin.
 *
 * @package     local_mxschool
 * @subpackage  user_management
 * @author      Jeremiah DeGreeff, Class of 2019 <jrdegreeff@mxschool.edu>
 * @author      Charles J McDonald, Academic Technology Specialist <cjmcdonald@mxschool.edu>
 * @copyright   2019 Middlesex School, 1400 Lowell Rd, Concord MA 01742 All Rights Reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_mxschool\local\user_management;

defined('MOODLE_INTERNAL') || die();

class parent_edit_form extends \local_mxschool\form {

    /**
     * Form definition.
     */
    protected function definition() {
        $students = $this->_customdata['students'];

        $fields = array(
            '' => array(
                'id' => self::ELEMENT_HIDDEN_INT
            ),
            'parent' => array(
                'student' => array('element' => 'select', 'options' => $students, 'rules' => array('required')),
                'name' => self::ELEMENT_TEXT_REQUIRED,
                'is_primary' => self::ELEMENT_BOOLEAN_REQUIRED,
                'relationship' => self::ELEMENT_TEXT_REQUIRED,
                'home_phone' => self::ELEMENT_TEXT,
                'cell_phone' => self::ELEMENT_TEXT,
                'work_phone' => self::ELEMENT_TEXT,
                'email' => self::ELEMENT_EMAIL_REQUIRED
            )
        );
        $this->set_fields($fields, 'user_management:parent_edit');
    }

}
