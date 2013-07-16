<?php
/**
 * Handles the input for the PHP wrapper
 * 
 * @author garland
 */

class Input{
    
    private $userJsonParameters_json;
    
    // System Variables
    private $class;
    private $function;
    private $authToken;
    private $job_id;
    private $algorithms_id;
    private $baseDomain; // URL of the enviroment that executing this
                         // example: https://api.algorithms.io
    
    private $error;
    
    public function __construct() {
    }
    public function getAuthToken(){
        return $this->authToken;
    }
    public function getJobId(){
        return $this->job_id;
    }
    /**
     * Takes in a json and checks if all the needed values are in the list.
     * If not, it will return false and the error.
     * 
     * @param json $systemParamsJson
     * @return boolean
     */
    public function setSystemParameterInput($systemParamsJson){
        if($this->isValidJSON($systemParamsJson)){
            $systemParamObj = json_decode($systemParamsJson);
        
            // Set user class
            if(isset($systemParamObj->class)){
                $this->class = $systemParamObj->class;
            }else{
                $this->error = "class not set.";
                return false;
            }
            // Set user function
            if(isset($systemParamObj->function)){
                $this->function = $systemParamObj->function;
            }else{
                $this->error = "function not set.";
                return false;
            }
            // Set AuthToken
            if(isset($systemParamObj->authToken)){
                $this->authToken = $systemParamObj->authToken;
            }else{
                $this->error = "authToken not set.";
                return false;
            }
            // Set job_id
            if(isset($systemParamObj->job_id)){
                $this->job_id = $systemParamObj->job_id;
            }else{
                $this->error = "job_id not set.";
                return false;
            }
            // Set algorithm_id
            if(isset($systemParamObj->algorithm_id)){
                $this->algorithm_id = $systemParamObj->algorithm_id;
            }else{
                $this->error = "algorithm_id not set.";
                return false;
            }
            // Set baseDomain
            if(isset($systemParamObj->baseDomain)){
                $this->baseDomain = $systemParamObj->baseDomain;
            }else{
                $this->error = "baseDomain not set.";
                return false;
            }
        }else{
            $this->error = 'invalid json';
            return false;
        }
        return true;
    }
    public function setUserParameterJson($userParams){
        $this->userJsonParameters_json = $userParams;
        return true;
    }
    public function getClass(){
        return $this->class;
    }
    public function getFunction(){
        return $this->function;
    }
    public function getAlgorithmId(){
        return $this->algorithm_id;
    }
    public function getBaseDomain(){
        return $this->baseDomain;
    }
    public function getUserParametersJson(){
        return $this->userJsonParameters_json;
    }
    public function getError(){
        return $this->error;
    }
    /**
    * Checks if parameter is a valid json
    */
   private function isValidJSON($json_string){
       $isValid = true;
       $jsonArray = json_decode($json_string);

       if(json_last_error()!=0)
           $isValid = false;

       return $isValid;
   }

   
}
?>
