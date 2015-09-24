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
//Error received
if($arrIssueList['message'] == "Not Found"){
    echo json_encode(array("code"=>0,"description"=>"Problem with URL","data"=>array()));
    die;
}
//No issues found
if(empty($arrIssueList)){
    echo json_encode(array("code"=>0,"description"=>"No Issues Found","data"=>array()));
    die;
}

// Iterating through the result.
// Calculating all differences and increasing the counts.
$arrResponse = array();
$intTotalIssueCount = 0;
$intTotalIssueIn24HoursCount = 0;
$intTotalIssueIn7DaysCount = 0;
$intTotalIssueBefore7DaysCount = 0;
foreach ($arrIssueList AS $key => $value) {

    $dateCreatedDate = $value['created_at'];
    $dateCreatedDate = str_replace("T", " ", $dateCreatedDate);
    $dateCreatedDate = str_replace("Z", "", $dateCreatedDate);
    $arrTimeDifference = getTimeDiff($dateCreatedDate);
    $intTotalIssueCount++;
    if ($arrTimeDifference <= 24) {
        $intTotalIssueIn24HoursCount++;
    }
    if ($arrTimeDifference > 24 && $arrTimeDifference <= 168) {
        $intTotalIssueIn7DaysCount++;
    }
    if ($arrTimeDifference > 168) {
        $intTotalIssueBefore7DaysCount++;
    }
}

//Generating a response
$arrResponse ['code'] = 1;
$arrResponse ['description'] = 'Success';
$arrResponse ['data']['total'] = $intTotalIssueCount;
$arrResponse ['data']['last24Hours'] = $intTotalIssueIn24HoursCount;
$arrResponse ['data']['thisWeek'] = $intTotalIssueIn7DaysCount;
$arrResponse ['data']['beforeThisWeek'] = $intTotalIssueBefore7DaysCount;

// returning response.
echo json_encode($arrResponse);
die;
// API ends.



//********************** Function definition STARTS *********************************/

/**
 * Function used to convert the url into desired format.
 * @param $strInputURL
 * @return string
 */
function convertURL($strInputURL){

    $strOutputURL = str_replace("github.com", "api.github.com/repos", $strInputURL).'?state=open';
    return $strOutputURL;
}

/**
 * Function will get the list of all issues.
 * @param $strInputURL
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

//********************** Function definition ENDS *********************************/