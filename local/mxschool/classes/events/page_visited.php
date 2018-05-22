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
 * Event for Middlesex School's Dorm and Student functions plugin that is triggered whenever a page is visited.
 *
 * @package    local_mxschool
 * @author     Jeremiah DeGreeff, Class of 2019 <jrdegreeff@mxschool.edu>
 * @author     Charles J McDonald, Academic Technology Specialist <cjmcdonald@mxschool.edu>
 * @copyright  2018, Middlesex School, 1400 Lowell Rd, Concord MA
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

namespace local_mxschool\event;

use \core\event\base;
use \context_system;

class page_visited extends base {

    /**
     * Initializes a page_visited event.
     */
    protected function init() {
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->context = context_system::instance();
    }

    /**
     * Retrieves the localized name of page_visited events.
     *
     * @return string the name of the event.
     */
    public static function get_name() {
        return get_string('event_page_visited', 'local_mxschool');
    }

    /**
     * Retrieves the unlocalized description of this page_visited event.
     *
     * @return string the description of the event.
     */
    public function get_description() {
        return "The user with id '{$this->userid}' visited page '{$this->other->page}'.";
    }

}