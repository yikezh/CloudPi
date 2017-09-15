<?php
$x = $_GET['input'];
//$x = "100";
require 'vendor/autoload.php';

use Aws\Sqs\SqsClient;
use Aws\Ec2\Ec2Client;
use Aws\S3\S3Client;

$clientsqs = SqsClient::factory(array(
    'key'    => 'AKIAIDCFRBQUTTB7GFLA',
    'secret' => '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',  
    'region'  => 'us-west-2'

));

$queueUrl ='https://sqs.us-west-2.amazonaws.com/125974270935/Myqueue';

$clientsqs->sendMessage(array(
    'QueueUrl'    => $queueUrl,
    'MessageBody' => $x,
));
//echo $x . "<br/>";

$ec2Client = Ec2Client::factory(array(
    'key'    => 'AKIAIDCFRBQUTTB7GFLA',
    'secret' => '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',
    'region' => 'us-west-2'
));

$keyPairName = 'TestKey';

$securityGroupName = 'launch-wizard-4';
$descript = $ec2Client->describeInstances(array(
    //'InstanceIds' => array('string', ... ),
    'Filters' => array(
        array(
            'Name' => 'instance-state-name',
            'Values' => array('running'),
        ),
    ),
    //'NextToken' => 'string',
    //'MaxResults' => integer,
));
$i = 0; 
$reservations = $descript['Reservations'];
foreach ($reservations as $reservation) {
    $instances = $reservation['Instances'];
    foreach ($instances as $instance) {
        $i++;
        }
       // echo '---> Instance ID: ' . $instance['InstanceId'] . PHP_EOL;
        //echo '---> State: ' . $instance['State']['Name'] . PHP_EOL;
}


$UDencode = base64_encode("#!/bin/bash\n php apptier.php ");

if ($i<11) {

    $result = $ec2Client->runInstances(array(
    'ImageId'        => 'ami-ba25abda',
    'MinCount'       => 1,
    'MaxCount'       => 1,
    'InstanceType'   => 't2.micro',
    'KeyName'        => $keyPairName,
    'SecurityGroups' => array($securityGroupName),
    'UserData'       => $UDencode
    ));

    $instanceIds = $result->getPath('Instances/*/InstanceId');
    $ec2Client->waitUntilInstanceRunning(array(
        'InstanceIds' => $instanceIds,
    ));

    $result = $ec2Client->describeInstances(array(
        'InstanceIds' => $instanceIds,
    ));
    //echo current($result->getPath('Reservations/*/Instances/*/PublicDnsName'));
}
//else
//{
//    echo "\nwaiting in the queue\n<br/>";
//}

$client = S3Client::factory(array(
    'key'    => 'AKIAIDCFRBQUTTB7GFLA',
    'secret' => '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',  
    'region'  => 'us-west-2'
));
$bucket = 'testbucket-1477';

while (1) {
	$iterator = $client->getIterator('ListObjects', array(
    'Bucket' => $bucket,
	));

	foreach ($iterator as $object) {
	    //echo $object['Key'] . "\n";
	    if ($object['Key'] == $x) {
	    	//echo $object['Key']  . "\n";
	    	//break;
    		$results3 = $client->getObject(array(
		    	'Bucket' => $bucket,
		    	'Key'    => $object['Key']
			));
			echo $results3['Body'] . "\n";
			exit(0);
	    }
	}
	sleep(1);
}

?>
