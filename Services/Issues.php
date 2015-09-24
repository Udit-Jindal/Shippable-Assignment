<?php
/**
 * Created by PhpStorm.
 * User: uditj
 * Date: 9/24/15
 * Time: 10:24 AM
 */


/**
 * Get List of all OPEN issues from GitHub
 * Get rid of useful information.
 * Get Total Count.
 * On the basis of created_at
 * Get number of issue opened in last 24 hours
 * Get number of issue opened in last 24 hours but less than 7 days
 * Get number of issue opened 7 days ago
 * Return response.
 */
// Setting default timezone
date_default_timezone_set('UTC');
$strInputURL = $_GET["url"];
$strInputURL = convertURL($strInputURL);
// Getting open Issue List
$arrIssueList = getIssueList($strInputURL);
if($arrIssueList['message'] == "Not Found" || isset($arrIssueList[0]['id'])!=1 ){
    echo json_encode(array("code"=>0,"description"=>"Problem with URL","data"=>array()));
    die;
}

$arrNewIssueList = array();
$intTotalIssueCount = 0;
foreach ($arrIssueList AS $key => $value) {
    $arrTemp['id'] = $value['id'];
    $arrTemp['title'] = $value['title'];
    $arrTemp['url'] = $value['url'];
    $dateCreatedDate = $value['created_at'];

    $dateCreatedDate = str_replace("T", " ", $dateCreatedDate);
    $dateCreatedDate = str_replace("Z", "", $dateCreatedDate);
    $arrTemp['created_at'] = $dateCreatedDate;
    $arrTimeDifference = getTimeDiff($dateCreatedDate);
    $arrTemp['diffInHours'] = $arrTimeDifference;
    array_push($arrNewIssueList, $arrTemp);
    $intTotalIssueCount++;

}

$arrResponse = array();
$intTotalIssueIn24Hours = 0;
$intTotalIssueIn7Days = 0;
$intTotalIssueBefore7Days = 0;
//getting all the count
foreach ($arrNewIssueList AS $key => $value) {
    if ($value['diffInHours'] <= 24) {
        $intTotalIssueIn24Hours++;
    }
    if ($value['diffInHours'] > 24 && $value['diffInHours'] <= 168) {
        $intTotalIssueIn7Days++;
    }
    if ($value['diffInHours'] > 168) {
        $intTotalIssueBefore7Days++;
    }
}
$arrResponse ['code'] = 1;
$arrResponse ['description'] = 'Success';
$arrResponse ['data']['total'] = $intTotalIssueCount;
$arrResponse ['data']['last24Hours'] = $intTotalIssueIn24Hours;
$arrResponse ['data']['thisWeek'] = $intTotalIssueIn7Days;
$arrResponse ['data']['beforeThisWeek'] = $intTotalIssueBefore7Days;

echo json_encode($arrResponse);
die;

function convertURL($strInputURL){

    $strOutputURL = str_replace("github.com", "api.github.com/repos", $strInputURL).'?state=open';
    return $strOutputURL;
}

/**
 * Function will get the list of all issues.
 * @return Array containing the list of issues
 */
function getIssueList($strInputURL)
{
    // Get cURL resource
    $curl = curl_init();
    // Set some options - passing uditjindal3@yahoo.com as user agent
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $strInputURL,
        CURLOPT_USERAGENT => 'uditjindal3@yahoo.com'
    ));
    // Send the request
    $jsonResponse = curl_exec($curl);
    // Close request
    curl_close($curl);
    return json_decode($jsonResponse,true);
}

/**
 * This function returns the time difference of current date with input date
 * @param Date in format 'YYYY-MM-DD HH:mm:ss'
 * @return difference of input date with current date
 */
function getTimeDiff($strInputDate)
{
    try {
        date_default_timezone_set('UTC');
        $objCreatedDate = new DateTime($strInputDate);
        $objCurrentDate = new DateTime();
        $objDiff = $objCurrentDate->diff($objCreatedDate);
        $intDays = $objDiff->days;
        $intHours = $objDiff->h + ($intDays * 24);
    } catch (Exception $e) {
        $intHours = 0;
    }
    return $intHours;
}