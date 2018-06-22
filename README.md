RAYNET CRM Integration
======================

This repository serve as a reference implementation for some of the API you can
use to integrate external systems with RAYNET CRM (https://raynetcrm.com). Each
example is contained in a separate folder:

* Example of creating a new Lead with a notification: */src/ExampleInsertLead* 
* Example of creating a new Task with a linked account and person:  */src/ExampleInsertActivity*

We recommend to use the class *RaynetCrmRestClient*, which serves as a simple
communication facade to the RAYNET CRM API.

Reference documentation to the REST API can be found here:
https://s3-eu-west-1.amazonaws.com/static-raynet/webroot/api-doc.html 

Requirements
------------

* PHP 5 (tested with PHP 5.5, although older should also work)
* Admin user account with API key (Application settings -> For developers -> API keys -> NEW API KEY)

Dependencies
------------

* Zend framework (https://framework.zend.com)

Code dependencies are managed via composer (https://getcomposer.org/). You can
download them using the command _php composer.phar install_.


Create a new Lead with a notification
=====================================

Here we show how to implement a simple web form with an integration to the RAYNET CRM.
The example implementation (_index.php_) allows to send a web form data to the CRM application
and record them as a new Lead. Notification (about the new Lead) can be send as optional behaviour. 

This snippet of a php code is responsible for the whole transaction:
```php
$crm = new RaynetCrmRestClient($instanceName, $userName, $apiKey);
$data = array(
    ... /* lead data, see the example */
    
    'notificationEmailAddresses' => array('email1@domain.com', 'email2@domain.com'),  /* email addresses that will be notified */
    'notificationMessage' => 'Lead has been created via web form'                     /* text that will be sent */
);

$result = $crm->createLead($data);
```

Method `createLead` accepts one argument:

* `$data` associative array that will be used to create a new lead. The only
	required field is _topic_. For all possible keys see the example in _index.php_.
  If the _notificationEmailAddresses_ attribute is specified (as an array of email addresses), than a notification will
  be send to these addresses with a message taken from the _notificationMessage_ attribute.

Method returns ID of the freshly created Lead.


Create a new activity with linked account and person
====================================================

This example demonstrates the possibilities of activity insert API.
Example shows, how to create a new task with linked account and person using RAYNET CRM API.
The account or person data can be left empty, in that case a _personal_ activity will be created.
When account or person data is filled, application will try to find corresponding records in the CRM.
If no relevant records are found, system will create new ones.

The implementation is more complex than in the previous example, so an new facade *CreateComplexActivityFacade*
was built upon the _RaynetCrmRestClient_ to make the usage a bit simpler. 

Again, a snippet of code follows:
```php
$crm = new CreateComplexActivityFacade($instanceName, $userName, $apiKey);
$activityData = array(
    ... /* activity data, see the example */
);
$personData = array(
    ... /* person data, see the example */
);
$accountData = array(
    ... /* account data, see the example */
);
$position = 'position in the company'

$result = $crm->createComplexTaskWithPersonOrCompanyContext($activityData, $personData, $accountData, $position);
```

Method `createComplexTaskWithPersonOrCompanyContext` accepts four arguments:

* `$activityData` associative array that will be used to create a new task.
    The required fields are _subject_, _owner_ and _deadline_. For all possible keys see _index.php_.
* `$personData` associative array that will be used to create a new person.
    The required fields are _lastName_ and _owner_. For all possible keys see _index.php_.
* `$accountData` associative array that will be used to create a new account.
	The required fields are _name_ and _owner_. For all possible keys see _index.php_.
* `$position` string value that represents person position in the company (if the corresponding account data is set).

Method returns ID of the freshly created Task.
