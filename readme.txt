Stack Details
OS Ubuntu 20.04.2 LTS (GNU/Linux 5.4.0-90-generic x86_64)
Apache/2.4.41 (Ubuntu)
MYSQL Server version: 8.0.27-0ubuntu0.20.04.1 (Ubuntu)
PHP 8.0.9

Initial design was with a light implementation of React.
It was functional, but got messy and I was disatissfied with the overall quality.
Shifted to heavier serverside app. Very little DOM tree modifications.

Hosted by digital ocean, the deployed app uses an unstructured MVC pattern built from scratch.
MVC pattern used primarily to enable clean routing.
CRUD operations are completed using a homespun ORM model 'DataObject', modified for use with MYSQL from MSSQL


Known Issues:
Task->Status should be normalized
Cancel should prompt to confirm if dirty form on EditTask.php
possible session contamination, only for filter session vars however
delete and complete task buttons piggy backs on updatetask.php. a dedicated action page would be better.
consider css root vars for color schemes
Add Password requirements and encrypt/hash
store db creds with an ecryption service
optomize for mobile

Testers Feedback
Wife: 	         Filters look off center on IOS mobile.
Kristy Story: 	 It's very plain.
Danielle Layton: Show Filters are confusing at first glance. Makes sense after a second.
Cami Sheridan:   The green check mark confused me. 
                 I would leave that white if I were you. 
                 And provide a legend of what the colors mean.
                 It looks very neat but can you add pictures? Most websites nowadays seem to be decorated
2 tester had no feedback
