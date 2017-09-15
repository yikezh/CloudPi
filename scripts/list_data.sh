#!/bin/sh



java -jar list_obj.jar

aws s3 ls s3://testbucket-1477
#aws s3api get-object --bucket testbucket-1477 --key 101 -
#echo $result
