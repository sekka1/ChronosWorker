<?php

include_once(dirname(__FILE__).'/../../../../PHP/AlgorithmsIO/InternalJobUpdates/Jobs.php');

class JobsTest extends PHPUnit_Framework_TestCase{
    
    public function testSubmitUpdate(){
         
        // Set return value for the cURL call
        $jobMock = $this->getMock('\AlgorithmsIO\InternalJobUpdates\Jobs', array('postCurlCall'));
        $jobMock->expects($this->any())
                ->method('postCurlCall')
                ->will($this->returnValue('{"status":"UPDATED"}')); 
        
        
        $jobMock->setUsersAuthToken('1234');
        $jobMock->setJobId('1234');
        $jobMock->setBaseDomainName('1234');
        $response = $jobMock->updateJobStatus('now');
        
        if($response)
            $this->assertTrue(true);
        else
            $this->assertTrue(false);
    }
    public function testSubmitUpdateFailedResponse(){
         
        // Set return value for the cURL call
        $jobMock = $this->getMock('\AlgorithmsIO\InternalJobUpdates\Jobs', array('postCurlCall'));
        $jobMock->expects($this->any())
                ->method('postCurlCall')
                ->will($this->returnValue('foo bar')); 
        
        
        $jobMock->setUsersAuthToken('1234');
        $jobMock->setJobId('1234');
        $jobMock->setBaseDomainName('1234');
        $response = $jobMock->updateJobStatus('now');
        
        if(! $response)
            $this->assertTrue(true);
        else
            $this->assertTrue(false);
    }
}
?>
