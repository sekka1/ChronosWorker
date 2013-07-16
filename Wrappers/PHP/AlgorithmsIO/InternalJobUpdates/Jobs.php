<?php
namespace AlgorithmsIO\InternalJobUpdates{

/**
 * This is an internal Algorithms.io class that is able to update
 * job statuses.
 * 
 * @author garland
 */
include_once(dirname(__FILE__).'/../SDK/Utilities.php');
    
    class Jobs{

        private $internalSystemAuth = '5b07d7a58d06a9990dccfaa4de8b4c0a8b0d5277d1788f920f148a7fd5aede2f';
        private $baseDomain = 'https://api.algorithms.io';
        private $url_path = '/v2jobinternal';
        
        private $errors;
        private $utilities;
        
        private $usersAuthToken;
        private $job_id;
        
        private $status;
        private $additional_info;

        public function __construct() {
            $this->utilities = new \Utilities();
        }
        public function setUsersAuthToken($authToken){
            $this->usersAuthToken = $authToken;
        }
        public function setJobId($job_id){
            $this->job_id = $job_id;
        }
        public function setBaseDomainName($domain){
            $this->baseDomain = $domain;
        }
        public function getErrors(){
            return $this->errors;
        }
        /**
         * Set the statues and update the job record
         * 
         * @param string $status
         * @param json $additional_info
         * @return type
         */
        public function updateJobStatus($status, $additional_info=null){
            $this->status = $status;
            $this->additional_info = $additional_info;
            return $this->submit();
        }
        /**
         * Sending the POST to Algorithms.io to update the jobs record
         * 
         * @return boolean
         */
        private function submit(){
            $post_params['authToken'] = $this->usersAuthToken;
            $post_params['internalSystemAuth'] = $this->internalSystemAuth;
            $post_params['job_id'] = $this->job_id;
            $post_params['status'] = $this->status;
            $post_params['additional_info'] = $this->additional_info;
            
            //$response = $this->utilities->curlPost($this->baseDomain.$this->url_path, $post_params);
            $response = $this->postCurlCall($this->baseDomain.$this->url_path, $post_params);
            $curInfo = $this->utilities->getLastCurlInfo();

            if($response == '{"status":"UPDATED"}'){
                $this->errors = 'Update Failed';
                return true;
            }
            else
                return false;
        }
        /**
         * Submits the POST call
         * 
         * Making this public so that the unit tests can "Mock" this functionality
         * and not actually call it.
         * 
         * @param string $url
         * @param array $post_params
         * @return string
         */
        public function postCurlCall($url, $post_params){
            return $this->utilities->curlPost($url, $post_params);
        }
    }
}
?>
