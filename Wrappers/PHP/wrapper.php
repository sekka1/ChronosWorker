<?php
/**
 * Wrapper script that will initiate the user's phar file
 * 
 * @author garland
 */
error_reporting(E_ALL);
include('Input.php');
include('AlgorithmsIO/InternalJobUpdates/Jobs.php');
include('AlgorithmsIO/SDK/Dataset.php');

$php_bin = '/usr/bin/php ';
$script_path = '/opt/UserPackages/PHP/';
$allOutputResults = '/opt/all_chronos_output_results/php_wrapper_results.txt';

/**
 * Set and validate incomming parameters
 */
$input = new Input();

$systemParams = base64_decode($argv[1]);
$userParamters = base64_decode($argv[2]);

$isValid_systemParams = $input->setSystemParameterInput($systemParams);
$isValid_userParams = $input->setUserParameterJson($userParamters);

if(!$isValid_systemParams){
    echo $input->getError();
    outputResultsToLocalFile($allOutputResults, 'Not Valid systemParams', $input->getError());
    exit;
}
if(!$isValid_userParams){
    echo $input->getError();
    outputResultsToLocalFile($allOutputResults, 'Not Valid userParams', $input->getError());
    exit;
}

/**
 * Start the process of executing the job
 */
$algorithms_job = new AlgorithmsIO\InternalJobUpdates\Jobs();
$algorithms_job->setBaseDomainName($input->getBaseDomain());
$algorithms_job->setJobId($input->getJobId());
$algorithms_job->setUsersAuthToken($input->getAuthToken());

// Update job_status: STARTING
$algorithms_job->updateJobStatus('STARTING');


// Execute
//$command = $php_bin.$script_path.$input->getAlgorithmId().".phar '".$input->getUserParametersJson()."'";
//$output = passthru($command);

//echo $output;
// Or
include($script_path.$input->getAlgorithmId().'.phar');
$paramertArray = json_decode($input->getUserParametersJson(),true);// datasources, input param strings
$class = $input->getClass();
$function = $input->getFunction();


// Execute User's Code
$userPhar = new $class();
$userPhar->$function($paramertArray);
echo $userPhar->getResults();
outputResultsToLocalFile($allOutputResults, $input->getJobId(), $userPhar->getResults());

// Capture result

// Update job_status: UPLOADING_RESULTS
$algorithms_job->updateJobStatus('UPLOADING_RESULTS');

// Upload result as a datasource
$dataset = new \AlgorithmsIO\SDK\Dataset();
$dataset->setBaseDomainName($input->getBaseDomain());
$dataset->setAuthToken($input->getAuthToken());

if($dataset->uploadData('Job:'.$input->getJobId(), $userPhar->getResults())){
    // Update job_stats: COMPLETED
    $algorithms_job->updateJobStatus('COMPLETED','{"datasource_id":"'.$dataset->getUploadedDataSetID().'"}', $dataset->getUploadedDataSetID());
}else{
    // Error uploading
    $algorithms_job->updateJobStatus('FAILED','{"error":"error uploading result dataset"}');
}


/**
 * Output results to a file
 * 
 * @param filePath $filePath
 * @param string $job_id
 * @param string $output
 */
function outputResultsToLocalFile($filePath, $job_id, $output){
    file_put_contents($filePath, 'job_id:'.$job_id."\n".$output."\n>>>>>>>>>>>>>>>>>>>>>>>>\n", FILE_APPEND);
}


?>
