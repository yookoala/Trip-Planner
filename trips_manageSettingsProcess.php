<?php

@session_start();

//Module includes
include "../../functions.php";
include "../../config.php";

include "./moduleFunctions.php";

date_default_timezone_set($_SESSION[$guid]["timezone"]);

$URL = $_SESSION[$guid]["absoluteURL"] . "/index.php?q=/modules/Trip Planner/trips_manageSettings.php";

$pdo = new Gibbon\sqlConnection();
$connection2 = $pdo->getConnection();

if (!isActionAccessible($guid, $connection2, '/modules/Trip Planner/trips_manageSettings.php')) {
    //Acess denied
    $URL .= "&return=error0";
    header("Location: {$URL}");
} else {

    $settings = array("requestApprovalType", "riskAssessmentTemplate", "missedClassWarningThreshold", "riskAssessmentApproval");

    foreach ($settings as $setting) {
        $value = null;
        if (isset($_POST[$setting])) {
            if ($_POST[$setting] != null && $_POST[$setting] != "") {
                if($setting == "missedClassWarningThreshold") {
                    $value = abs($_POST[$setting]);
                } else {
                    $value = $_POST[$setting];
                }
            }
        } elseif($setting == "riskAssessmentApproval") {
            $value = 0;
        }

        if ($value == null) {
            $URL .= "&return=error1";
            header("Location: {$URL}");
        }

        try {
            $data = array("value" => $value, "setting" => $setting);
            $sql = "UPDATE gibbonSetting SET value=:value WHERE scope='Trip Planner' AND name=:setting;";
            $result = $connection2->prepare($sql);
            $result->execute($data);
        } catch (PDOException $e) {
            $URL .= "&return=error2";
            header("Location: {$URL}");
        }
    }
 
    $URL .= "&return=success0";
    header("Location: {$URL}");
}   
?>
