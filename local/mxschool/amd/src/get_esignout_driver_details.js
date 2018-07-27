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
 * Updates the destination and departure fields of the eSignout form for Middlesex School's Dorm and Student functions plugin.
 *
 * @module     local_mxschool/get_esignout_driver_details
 * @package    local_mxschool
 * @author     Jeremiah DeGreeff, Class of 2019 <jrdegreeff@mxschool.edu>
 * @author     Charles J McDonald, Academic Technology Specialist <cjmcdonald@mxschool.edu>
 * @copyright  2018, Middlesex School, 1400 Lowell Rd, Concord MA
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax'], function($, ajax) {
    function update () {
        var promises = ajax.call([{
            methodname: 'local_mxschool_get_esignout_driver_details',
            args: {
                esignoutid: $('.mx-form select#id_driver').val()
            }
        }]);
        promises[0].done(function(data) {
            $('.mx-form input#id_destination').val(data.destination);
            $('.mx-form select#id_departuretime_hour').val(data.departurehour);
            $('.mx-form select#id_departuretime_minute').val(data.departureminute);
            $('.mx-form select#id_departuretime_ampm').val(data.departureampm ? 1 : 0);
        }).fail(function() {
            $('.mx-form input#id_destination').val(function() {return this.defaultValue;});
            $('.mx-form select#id_departuretime_hour > option').prop('selected', function() {return this.defaultSelected;});
            $('.mx-form select#id_departuretime_minute > option').prop('selected', function() {return this.defaultSelected;});
            $('.mx-form select#id_departuretime_ampm > option').prop('selected', function() {return this.defaultSelected;});
        });
    }
    return function() {
        $('.mx-form select#id_driver').change(update);
    };
});
