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
 * Updates the options of the on-campus signout form for Middlesex's eSignout Subplugin.
 *
 * @module      local_signout/on_campus_form
 * @package     local_signout
 * @subpackage  on_campus
 * @author      Jeremiah DeGreeff, Class of 2019 <jrdegreeff@mxschool.edu>
 * @author      Charles J McDonald, Academic Technology Specialist <cjmcdonald@mxschool.edu>
 * @copyright   2019 Middlesex School, 1400 Lowell Rd, Concord MA 01742 All Rights Reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(
    ['jquery', 'core/ajax', 'core/str', 'core/notification', 'local_mxschool/locallib'], function($, ajax, str, notification, lib) {
        function addClasses() {
            $('.mx-form div#fitem_id_location_warning').children().eq(1).children().eq(0).addClass('text-warning');
        }
        function updateStudentOptions() {
            var promises = ajax.call([{
                methodname: 'local_signout_get_on_campus_student_options',
                args: {
                    userid: $('.mx-form select#id_student').val(),
                    locationid: $('.mx-form select#id_location_select').val()
                }
            }]);
            promises[0].done(function(data) {
                $.when(
                    str.get_string('form:select:default', 'local_mxschool'),
                    str.get_string('on_campus:form:info:location_select:other', 'local_signout')
                ).done(function(select, other) {
                    data.locations.unshift({
                        value: 0,
                        text: select
                    });
                    data.locations.push({
                        value: -1,
                        text: other
                    });
                    lib.updateSelect($('.mx-form select#id_location_select'), data.locations);
                });
                var permissionsFieldset = $('.mx-form fieldset#id_permissions');
                var warningText = permissionsFieldset.children().eq(1).children().eq(0).children().eq(1).children().eq(0);
                if (data.warning) {
                    warningText.html(data.warning);
                    permissionsFieldset.next().hide();
                    permissionsFieldset.show();
                } else {
                    warningText.text('');
                    permissionsFieldset.next().show();
                    permissionsFieldset.hide();
                }
            }).fail(notification.exception);
        }
        return {
            setup: function() {
                $(document).ready(function() {
                    addClasses();
                    updateStudentOptions();
                });
                $('.mx-form select#id_student').change(updateStudentOptions);
                $('.mx-form select#id_location_select').change(updateStudentOptions);
            }
        };
    }
);
