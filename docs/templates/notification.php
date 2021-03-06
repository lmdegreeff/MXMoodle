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
 * TODO: Class Description.
 *
 * @package     PACKAGE
 * @subpackage  SUBPACKAGE
 * @author      PRIMARY AUTHOR
 * @author      Charles J McDonald, Academic Technology Specialist <cjmcdonald@mxschool.edu>
 * @copyright   2019 Middlesex School, 1400 Lowell Rd, Concord MA 01742 All Rights Reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace PACKAGE\local\SUBPACKAGE;

defined('MOODLE_INTERNAL') || die;

class NOTIFICATION_NAME extends local_mxschool\notification {

    /**
     * TODO: Description of any parameters or exceptions.
     */
    public function __construct() {
        global $DB;
        parent::__construct('EMAIL_CLASS');

        // TODO: query DB.
        // TODO: populate $this->data.
        // TODO: populate $this->recipients.
    }

    /**
     * @return array The list of strings which can serve as tags for the notification.
     */
    public function get_tags() {
        return array_merge(parent::get_tags(), array(
            // TODO: List additional tags.
        ));
    }

}
