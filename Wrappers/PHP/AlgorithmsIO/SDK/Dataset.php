<?php
namespace AlgorithmsIO\SDK{

    include_once('Utilities.php');
    
    /**
     * Algorithms.io Dataset functionality
     * 
     * @author
     */
    class Dataset{
        
        private $baseDomain = 'https://api.algorithms.io';
        private $url_path = '/dataset';
        private $tempFileLocation = '/tmp/';
        
        private $authToken;
        
        private $utilities;
        
        private $responseObj;
        
        public function __construct() {
            $this->utilities = new \AlgorithmsIO\Utilities();
        }
        public function setAuthToken($authToken){
            $this->authToken = $authToken;
        }
        public function setBaseDomainName($domain){
            $this->baseDomain = $domain;
        }
        /**
         * Uploads data into Algorithms.io as a dataset.
         * 
         * Pass in a name for the file and a text string.
         * 
         * @param string $name
         * @param string $data
         * @return boolean
         */
        public function uploadData($name, $data){
            $this->writeStringToFile($name, $data);
            
            $post_params['authToken'] = $this->authToken;
            $post_params['name'] = $name;
            $post_params['theFile'] = '@'.$this->tempFileLocation.$name;
            
            //$response_json = $this->utilities->curlPost($this->baseDomain.$this->url_path, $post_params);
            $response_json = $this->postCurlCall($this->baseDomain.$this->url_path, $post_params);
            $this->removeFile($name);
            
            $this->responseObj = json_decode($response_json);
       
            if(json_last_error()!=0)
                return false;
            
            if(! $this->isUploadAuthenticationSuccessful())
                return false;
            
            return true;
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
        public function getUploadedDataSetID(){
            if(json_last_error()!=0)
                return -1;
            if(! $this->isUploadAuthenticationSuccessful())
                return -1;
            return $this->getDatasetID();
        }
        private function isUploadAuthenticationSuccessful(){
            if(!isset($this->responseObj[0]->api->Authentication))
                return false;
            if($this->responseObj[0]->api->Authentication!="Success")
                return false;
            
            return true;
        }
        private function getDatasetID(){
            if(! isset($this->responseObj[0]->data))
                return -1;
            else
                return $this->responseObj[0]->data;
        }
        private function writeStringToFile($fileName, $string){
            file_put_contents($this->tempFileLocation.$fileName, $string);
        }
        private function removeFile($fileName){
            unlink($this->tempFileLocation.$fileName);
        }
        
    }
}
?>
