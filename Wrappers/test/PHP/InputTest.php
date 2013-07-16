<?php

include_once(dirname(__FILE__).'/../../PHP/Input.php');

class InputTest extends PHPUnit_Framework_TestCase{
    
    public function testSetSystemParameterInputGoodInput(){
        
        $good_json = '{"class":"Gar","function":"runMe","authToken":"b81edbfe20848304c07f0dd368fcc3fd","job_id":"d132d81b0a6c3f25b4d93bcdc1bd81b7","algorithm_id":"201","baseDomain":"http:\/\/pod3.staging.v1.api.algorithms.io"}';
        
        $input = new Input();
        $result = $input->setSystemParameterInput($good_json);
        
        if($result)
            $this->assertTrue(true);
        else
            $this->assertTrue(false);
    }
    public function testSetSystemParameterInputBadJSON(){
        
        $good_json = 'zzzzzzz{"class":"Gar","function":"runMe","authToken":"b81edbfe20848304c07f0dd368fcc3fd","job_id":"d132d81b0a6c3f25b4d93bcdc1bd81b7","algorithm_id":"201","baseDomain":"http:\/\/pod3.staging.v1.api.algorithms.io"}';
        
        $input = new Input();
        $result = $input->setSystemParameterInput($good_json);
        
        if(! $result)
            $this->assertTrue(true);
        else
            $this->assertTrue(false);
        
        $this->assertEquals('invalid json', $input->getError());
    }
    public function testSetSystemParameterInputMissingAuthToken(){
        
        $good_json = '{"class":"Gar","function":"runMe","job_id":"d132d81b0a6c3f25b4d93bcdc1bd81b7","algorithm_id":"201","baseDomain":"http:\/\/pod3.staging.v1.api.algorithms.io"}';
        
        $input = new Input();
        $result = $input->setSystemParameterInput($good_json);
        
        if(! $result)
            $this->assertTrue(true);
        else
            $this->assertTrue(false);
        
        $this->assertEquals('authToken not set.', $input->getError());
    }
    public function testSetSystemParameterInputMissingClassParam(){
        
        $good_json = '{"function":"runMe","authToken":"b81edbfe20848304c07f0dd368fcc3fd","job_id":"d132d81b0a6c3f25b4d93bcdc1bd81b7","algorithm_id":"201","baseDomain":"http:\/\/pod3.staging.v1.api.algorithms.io"}';
        
        $input = new Input();
        $result = $input->setSystemParameterInput($good_json);
        
        if(! $result)
            $this->assertTrue(true);
        else
            $this->assertTrue(false);
        
        $this->assertEquals('class not set.', $input->getError());
    }
}
?>
