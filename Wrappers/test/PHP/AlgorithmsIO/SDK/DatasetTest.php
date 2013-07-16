<?php

include_once(dirname(__FILE__).'/../../../../PHP/AlgorithmsIO/SDK/Dataset.php');

class DatasetTest extends PHPUnit_Framework_TestCase{
    
    public function testUploadData(){
         
        // Set return value for the cURL call
        $mock = $this->getMock('\AlgorithmsIO\SDK\Dataset', array('postCurlCall'));

        $mock->expects($this->any())
                ->method('postCurlCall')
                ->will($this->returnValue('[{"api":{"Authentication":"Success"},"data":4304}]')); 
        
        // Set variables and run upload
        $mock->setBaseDomainName('1234');
        $mock->setAuthToken('1234');
        $response = $mock->uploadData('name', 'some fake data');
   
        if($response)
            $this->assertTrue(true);
        else
            $this->assertTrue(false);
    }
    public function testCheckUploadedDatasetID(){
         
        // Set return value for the cURL call
        $mock = $this->getMock('\AlgorithmsIO\SDK\Dataset', array('postCurlCall'));

        $mock->expects($this->any())
                ->method('postCurlCall')
                ->will($this->returnValue('[{"api":{"Authentication":"Success"},"data":4304}]')); 
        
        // Set variables and run upload
        $mock->setBaseDomainName('1234');
        $mock->setAuthToken('1234');
        $mock->uploadData('name', 'some fake data');
        //$dataset_id = $mock->getUploadedDataSetID();
   
        $this->assertEquals(4304, $mock->getUploadedDataSetID());
    }
}
?>
