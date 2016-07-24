<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

@session_start();

//Module includes
include "./modules/Trip Planner/moduleFunctions.php";

if (!isActionAccessible($guid, $connection2, '/modules/Trip Planner/trips_submitRequest.php')) {
    //Acess denied
    print "<div class='error'>";
        print "You do not have access to this action.";
    print "</div>";
} else {
    print "<div class='trail'>";
        print "<div class='trailHead'><a href='" . $_SESSION[$guid]["absoluteURL"] . "'>" . _("Home") . "</a> > <a href='" . $_SESSION[$guid]["absoluteURL"] . "/index.php?q=/modules/" . getModuleName($_GET["q"]) . "/" . getModuleEntry($_GET["q"], $connection2, $guid) . "'>" . _(getModuleName($_GET["q"])) . "</a> > </div><div class='trailEnd'>" . _('Submit Trip Request') . "</div>";
    print "</div>";

    if (isset($_GET['return'])) {
        returnProcess($guid, $_GET['return'], null, null);
    }

    print "<h3>";
        print "Request";
    print "</h3>";
    ?>

    <script type="text/javascript">
        function optionTransfer(select0Name, select1Name) {
            var select0 = document.getElementById(select0Name);
            var select1 = document.getElementById(select1Name);
            for (var i = select0.length - 1; i>=0; i--) {
                var option = select0.options[i];
                if (option.selected) {
                    select0.remove(i);
                    try {
                        select1.add(option, null);
                    } catch (ex) {
                        select1.add(option);
                    }
                }
            }
            sortSelect(select0);
            sortSelect(select1);
        }

        function sortSelect(list) {
            var tempArray = new Array();
            for (var i=0;i<list.options.length;i++) {
                tempArray[i] = new Array();
                tempArray[i][0] = list.options[i].text;
                tempArray[i][1] = list.options[i].value;
            }
            tempArray.sort();
            while (list.options.length > 0) {
                list.options[0] = null;
            }
            for (var i=0;i<tempArray.length;i++) {
                var op = new Option(tempArray[i][0], tempArray[i][1]);
                list.options[i] = op;
            }
            return;
        }

        function submitForm() {
            var teachers = document.getElementById('teachers1');
            var students = document.getElementById('students1');
            var container = document.getElementById('finalData');
            for (var i = 0; i < Math.max(teachers.length, students.length); i++) {
                var teacher = teachers.options[i];
                var student = students.options[i];
                if (teacher != null) {
                    var teachersSelected = document.createElement("input");
                    teachersSelected.setAttribute('type', 'hidden');
                    teachersSelected.setAttribute('name', 'teachersSelected[]');
                    teachersSelected.setAttribute('value', teacher.value);
                    container.appendChild(teachersSelected);
                }

                if (student != null) {
                    var studentsSelected = document.createElement("input");
                    studentsSelected.setAttribute('type', 'hidden');
                    studentsSelected.setAttribute('name', 'studentsSelected[]');
                    studentsSelected.setAttribute('value', student.value);
                    container.appendChild(studentsSelected);
                }
            }
        }
    </script>

    <form method="post" name="requestForm" action="<?php print $_SESSION[$guid]["absoluteURL"] . "/modules/Trip Planner/trips_submitRequestProcess.php" ?>" onsubmit="submitForm(); return true;">
        <table class='smallIntBorder' cellspacing='0' style="width: 100%">
            <tr>
                <td style='width: 275px'>
                    <b><?php print _('Title') ?> *</b><br/>
                </td>
                <td class="right">
                    <input name="title" id="title" maxlength=60 value="" type="text" style="width: 300px">
                    <script type="text/javascript">
                        var title=new LiveValidation('title');
                        title.add(Validate.Presence);
                    </script>
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <b><?php print _('Description') ?> *</b><br/>
                    <?php print getEditor($guid, TRUE, "description", "", 5, true, true, false); ?>               
                </td>
            </tr>
            <tr>
                <td> 
                    <b><?php print _('Date') ?> *</b><br/>
                    <span style="font-size: 90%"><i><?php print $_SESSION[$guid]["i18n"]["dateFormat"]  ?></i></span>
                </td>
                <td class="right">
                    <input name="date" id="date" maxlength=10 value="" type="text" style="width: 300px">
                    <script type="text/javascript">
                        var date=new LiveValidation('date');
                        date.add(Validate.Presence);
                        date.add( Validate.Format, {pattern: <?php if ($_SESSION[$guid]["i18n"]["dateFormatRegEx"]=="") {  print "/^(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d$/i"; } else { print $_SESSION[$guid]["i18n"]["dateFormatRegEx"]; } ?>, failureMessage: "Use <?php if ($_SESSION[$guid]["i18n"]["dateFormat"]=="") { print "dd/mm/yyyy"; } else { print $_SESSION[$guid]["i18n"]["dateFormat"]; }?>." } ); 
                    </script>
                     <script type="text/javascript">
                        $(function() {
                            $( "#date" ).datepicker();
                        });
                    </script>
                </td>
            </tr>
            <tr>
                <td> 
                    <b><?php print _('Start Time') ?> *</b><br/>
                    <span style="font-size: 90%"><i><?php print _('Format: hh:mm (24hr)') ?><br/></i></span>
                </td>
                <td class="right">
                    <input name="startTime" id="startTime" maxlength=5 value="" type="text" style="width: 300px">
                    <script type="text/javascript">
                        var startTime=new LiveValidation('startTime');
                        startTime.add(Validate.Presence);
                        startTime.add( Validate.Format, {pattern: /^(0[0-9]|[1][0-9]|2[0-3])[:](0[0-9]|[1-5][0-9])/i, failureMessage: "Use hh:mm" } ); 
                    </script>
                </td>
            </tr>
            <tr>
                <td> 
                    <b><?php print _('End Time') ?> *</b><br/>
                    <span style="font-size: 90%"><i><?php print _('Format: hh:mm (24hr)') ?><br/></i></span>
                </td>
                <td class="right">
                    <input name="endTime" id="endTime" maxlength=5 value="" type="text" style="width: 300px">
                    <script type="text/javascript">
                        var endTime=new LiveValidation('endTime');
                        endTime.add(Validate.Presence);
                        endTime.add( Validate.Format, {pattern: /^(0[0-9]|[1][0-9]|2[0-3])[:](0[0-9]|[1-5][0-9])/i, failureMessage: "Use hh:mm" } ); 
                    </script>
                </td>
            </tr>
            <tr> 
                <td style='width: 275px'>
                    <b><?php print _('Location') ?> *</b><br/>
                </td>
                <td>
                    <input name="location" id="location" maxlength=60 value="" type="text" style="width: 300px">
                    <script type="text/javascript">
                        var loc=new LiveValidation('location');
                        loc.add(Validate.Presence);
                    </script>

                </td>
            </tr>
            <tr>
                <td> 
                    <b><?php print __($guid, 'Total Cost') ?> *</b><br/>
                    <span style="font-size: 90%">
                        <i>
                        <?php
                        if ($_SESSION[$guid]["currency"] != "") {
                            print sprintf(__($guid, 'Numeric value of the fee in %1$s.'), $_SESSION[$guid]["currency"]);
                        } else {
                            print __($guid, "Numeric value of the fee.");
                        }
                        ?>
                        </i>
                    </span>
                </td>
                <td class="right">
                    <input name="totalCost" id="totalCost" maxlength=15 value="" type="text" style="width: 300px">
                    <script type="text/javascript">
                        var totalCost=new LiveValidation('totalCost');
                        totalCost.add(Validate.Presence);
                        totalCost.add( Validate.Format, { pattern: /^(?:\d*\.\d{1,2}|\d+)$/, failureMessage: "Invalid number format!" } );
                    </script>
                </td>
            </tr>
            <tr class='break'>
                <td colspan=2> 
                    <h3><?php print __($guid, 'Costs') ?></h3>
                </td>
            </tr>
            <?php 
                $type="cost"; 
            ?> 
            <style>
                #<?php print $type ?> { list-style-type: none; margin: 0; padding: 0; width: 100%; }
                #<?php print $type ?> div.ui-state-default { margin: 0 0px 5px 0px; padding: 5px; font-size: 100%; min-height: 58px; }
                div.ui-state-default_dud { margin: 5px 0px 5px 0px; padding: 5px; font-size: 100%; min-height: 58px; }
                html>body #<?php print $type ?> li { min-height: 58px; line-height: 1.2em; }
                .<?php print $type ?>-ui-state-highlight { margin-bottom: 5px; min-height: 58px; line-height: 1.2em; width: 100%; }
                .<?php print $type ?>-ui-state-highlight {border: 1px solid #fcd3a1; background: #fbf8ee url(images/ui-bg_glass_55_fbf8ee_1x400.png) 50% 50% repeat-x; color: #444444; }
            </style>
            <tr>
                <td colspan=2> 
                    <div class="cost" id="cost" style='width: 100%; padding: 5px 0px 0px 0px; min-height: 66px'>
                        <div id="costOuter0">
                            <div style='color: #ddd; font-size: 230%; margin: 15px 0 0 6px'><?php print __($guid, 'Costs will be listed here...') ?></div>
                        </div>
                    </div>
                    <div style='width: 100%; padding: 0px 0px 0px 0px'>
                        <div class="ui-state-default_dud" style='padding: 0px; height: 40px'>
                            <table class='blank' cellspacing='0' style='width: 100%'>
                                <tr>
                                    <td style='width: 50%'>
                                        <script type="text/javascript">
                                            var costCount=1;
                                        </script>
                                        <input type="button" value="New Cost" style='float: none; margin-left: 3px; margin-top: 0px; width: 350px' onclick="addCost()" />
                                        <?php
                                            $costBlock = "$('#cost').append('<div id=\"costOuter' + costCount + '\"><img style=\"margin: 10px 0 5px 0\" src=\"" . $_SESSION[$guid]["absoluteURL"] . "/themes/Default/img/loading.gif\" alt=\"Loading\" onclick=\"return false;\" /><br/>Loading</div>');";
                                            $costBlock .= "$(\"#costOuter\" + costCount).load(\"" . $_SESSION[$guid]["absoluteURL"] . "/modules/Trip%20Planner/trips_submitRequestAddBlockCostAjax.php\",\"id=\" + costCount);";
                                            $costBlock .= "costCount++;";
                                            //$costBlock.="$('#newCost').val('0');";
                                        ?>
                                        <script type='text/javascript'>
                                            function addCost() {
                                                $("#costOuter0").css("display", "none");
                                                <?php print $costBlock ?>
                                            }
                                        </script>
                                        
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <?php
                    try {
                        $sql = "SELECT value FROM gibbonSetting WHERE scope='Trip Planner' AND name='riskAssessmentTemplate'";
                        $result = $connection2->prepare($sql);
                        $result->execute();
                    } catch (PDOException $e) { 
                        print "<div class='error'>" . $e->getMessage() . "</div>"; 
                    }
                    $row = $result->fetch();
                ?>
                <td colspan=2>
                    <b><?php print _('Risk Assessment') ?> *</b><br/>
                    <?php print getEditor($guid, TRUE, "riskAssessment", $row["value"], 5, true, true, false); ?>               
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <b><?php print _('Teachers') ?> *</b></br>
                    <select name='teachers' id='teachers' multiple style="width: 302px; height: 150px; margin-left: 0px !important; float: left;">
                        <?php
                            try {
                                $sql = "SELECT gibbonPersonID, preferredName, surname FROM gibbonPerson WHERE gibbonRoleIDPrimary=002 AND status='Full' ORDER BY preferredName, surname ASC";
                                $result = $connection2->prepare($sql);
                                $result->execute();
                            } catch (PDOException $e) {
                            }

                            while (($row = $result->fetch()) != null) {
                                print "<option value='" . $row["gibbonPersonID"] . "'>" . $row["preferredName"] . " " . $row["surname"] . "</option>";
                            }
                        ?>
                    </select>
                    <div style="float: left; width: 136px; height: 148px; display: table;">
                        <div style="display: table-cell; vertical-align: middle; text-align:center;">
                            <!-- <input id="teacherFilter" align="absmiddle" maxlength=60 value="" type="text" style="width: 73%; margin-right: 12.5%" onchange="filterTeachers()" /></br> -->
                            <input type="button" value="Add" style="width: 75%;" onclick="optionTransfer('teachers', 'teachers1')" /></br>
                            <input type="button" value="Remove" style="width: 75%;" onclick="optionTransfer('teachers1', 'teachers')" />
                        </div>
                    </div>
                    <select name='teachers1' id='teachers1' multiple style="float: right; margin-left: 0px !important; width: 302px; height: 150px;"></select>
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <b><?php print _('Students') ?> *</b></br>
                    <select name='students' id='students' multiple style="width: 302px; height: 150px; margin-left: 0px !important; float: left;">
                        <?php
                            try {
                                $data=array();
                                $sql="SELECT gibbonPersonID, preferredName, surname FROM gibbonPerson WHERE gibbonRoleIDPrimary=003 AND status='Full' ORDER BY preferredName, surname ASC";
                                $result=$connection2->prepare($sql);
                                $result->execute($data);
                            } catch (PDOException $e) {
                            }

                            while (($row = $result->fetch()) != null) {
                                print "<option value='" . $row["gibbonPersonID"] . "'>" . $row["preferredName"] . " " . $row["surname"] . "</option>";
                            }
                        ?>
                    </select>
                    <div style="float: left; width: 136px; height: 148px; display: table; text-align:center;">
                        <div style="display: table-cell; vertical-align: middle;">
                            <input type="button" value="Add" style="width: 75%;" onclick="optionTransfer('students', 'students1')" /></br>
                            <input type="button" value="Remove" style="width: 75%;" onclick="optionTransfer('students1', 'students')" />
                        </div>
                    </div>
                    <select name='students1' id='students1' multiple style="float: right; margin-left: 0px !important; width: 302px; height: 150px;"></select>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="font-size: 90%"><i>* <?php print _("denotes a required field"); ?></i></span>
                </td>
                <td class="right" id="finalData">
                    <input type="hidden" name="address" value="<?php print $_SESSION[$guid]["address"] ?>">
                    <input type="submit" value="<?php print _("Submit"); ?>">
                </td>
            </tr>
        </table>
    </form>
    <?php

}   
?>