# CloudPi
This is an elastic application called CloudPi, which uses cloud resources to compute the digits of Pi. 
When a user makes a HTTP request with a input x, where x is the input of this program, he will get the output in a HTML page.

It follows the typical three-tier architecture: 
1) the web tier services the requests for computing Pi; 
2) the application tier performs the computation; 
3) the data tier stores the computing result.

All these three tiers are deployed on AWS. They are linked by Simple Queue Service of Amazon Web Services.

Web-tier:

When a request is made from browser, the program of web tier, cloudpi.php, would start up. 


Firstly, it will send the input to the queque.


Then, it will detect the number of running instance, and when there is no more than 10 running instances, web tier will create a new instance. 


Finally, it will search the input's result in the bucket until it find the result and print it in HTML page.



app-tier: 

When an app-tier instance is launched, a program called apptier.php would run automatically. 


This program will get a massage from the queue, then create a file called pi.in and write this message in it and call the exe file to compute the result, and store it in the bucket.


It will repeat the above procedure until there is no message in the queue and terminate itself. 


Data tier
Stored all data in Amazon Simple Storage Service