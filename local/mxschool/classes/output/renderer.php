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
 * Renderer for Middlesex School's Dorm and Student functions plugin.
 *
 * @package    local_mxschool
 * @author     Jeremiah DeGreeff, Class of 2019 <jrdegreeff@mxschool.edu>
 * @author     Charles J McDonald, Academic Technology Specialist <cjmcdonald@mxschool.edu>
 * @copyright  2018, Middlesex School, 1400 Lowell Rd, Concord MA
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

namespace local_mxschool\output;

use plugin_renderer_base;

class renderer extends plugin_renderer_base {

    /**
     * Renders an index page according to the template.
     *
     * @param index_page $page.
     *
     * @return string html for the page.
     */
    public function render_index_page($page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('local_mxschool/index_page', $data);
    }

    /**
     * Renders a report page according to the template.
     *
     * @param report_page $page.
     *
     * @return string html for the page.
     */
    public function render_report_page($page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('local_mxschool/report_page', $data);
    }

    /**
     * Renders a form page according to the template.
     *
     * @param form_page $page.
     *
     * @return string html for the page.
     */
    public function render_form_page($page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('local_mxschool/form_page', $data);
    }

}
