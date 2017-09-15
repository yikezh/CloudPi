#!/bin/sh

aws s3 rm s3://testbucket-1477 --recursive


aws ec2 start-instances --instance-ids i-0696d8bd6d34a8dfb --region us-west-2 --output table
# web tier


#aws ec2 start-instances --instance-ids i-0891a84fc43346100 --region us-west-2
#test instance
