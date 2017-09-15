<?php
require 'vendor/autoload.php';

use Aws\Sqs\SqsClient;
use Aws\S3\S3Client;
use Aws\Ec2\Ec2Client;
while(1)
{
    $clientEc2 = Ec2Client::factory(array(
    'key'    => 'AKIAIDCFRBQUTTB7GFLA',
    'secret' => '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',
    'region' => 'us-west-2',
    'version' => 'latest'
    ));

    $client = SqsClient::factory(array(
        'key' => 'AKIAIDCFRBQUTTB7GFLA',
        'secret' => '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',
        'region'  => 'us-west-2'
    ));

    $queueUrl = 'https://sqs.us-west-2.amazonaws.com/125974270935/Myqueue';
    $result = $client->receiveMessage(array(
        'QueueUrl' => $queueUrl,
        //'WaitTimeSeconds' => 2,
    ));

   // echo "start\n";
    if(NULL == ($result->getPath('Messages/*/Body')))
    {
        echo "The Queue is Empty\n";
        $id = @file_get_contents("http://instance-data/latest/meta-data/instance-id");
        $resultEc2 = $clientEc2->terminateInstances(array(
           
           
            'InstanceIds' => array($id),
        ));
        exit(0);
    }
    else
    {
        foreach ($result->getPath('Messages/*/Body') as $messageBody) { 
        // Do something with the message
    		echo $messageBody . "\n";
        
        }
    }

    foreach ($result->getPath('Messages/*/ReceiptHandle') as $Handle) {
        // Do something with the message
    //    echo $Handle;
    }
    //echo "\n";

    $myfile = fopen("pi.in", "w");
    fwrite($myfile, $messageBody);
    fclose($myfile);
    $re = $client -> deleteMessage(array(
        'QueueUrl' => $queueUrl,
        'ReceiptHandle' => $Handle,
    ));
    $output = system("./pifft pi.in");
    echo "\n";
    $clients3 = S3Client::factory(array(
        'key' => 'AKIAIDCFRBQUTTB7GFLA',
        'secret' => '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',
    ));
    // Upload an object to Amazon S3
    $results3 = $clients3->putObject(array(
        'Bucket' => 'testbucket-1477',
        'Key'    => $messageBody,
        'Body'   => $output
    ));

    // Access parts of the result object
    //echo $results3['Expiration'] . "\n";
    //echo $results3['ServerSideEncryption'] . "\n";
    //echo $results3['ETag'] . "\n";
    //echo $results3['VersionId'] . "\n";
    //echo $results3['RequestId'] . "\n";

    // Get the URL the object can be downloaded from
    echo $results3['ObjectURL'] . "\n";
  //  echo "end\n";
    system("rm pi.in");
    echo "remove pi.in\n";
    sleep(1);

}

?>
