#!/bin/bash

java -jar list_instance.jar

aws ec2 describe-instances --query 'Reservations[*].Instances[*].[State.Name, InstanceId]' --output text 


#aws ec2 describe-instances --filcloud ters "Name=image-id,Values=ami-x0123456,ami-0fe6686f,ami-ba25abda" --region us-west-2 


#aws ec2 describe-instances --filters "Name=instance-type,Values=t2.micro" --region us-west-2

#echo $temp
