Create a new lead in RAYNET CRM
===============================

```php
$crm = new Raynetcrm($instanceName, $userName, $apiKey);
$data = array( /* see example */ );

$result = $crm->insertLead($data, array('notification-email@example.com'), 'Lead has been created via web form');
```

Method `insertLead` accepts three arguments:

* `$data` associative array that will be used to create a new lead. The only
	required field is _subject_. For all possible keys see the example in
	_index.php_.
* `$notifyUserList` array of usernames (emails) that will be notified on
	successfull lead creattion.
* `$notifyUserMessage` notification message

Method returns true or false, depending on the success of the operation.

Special care is required while handling the picklist values (category, source,
...). Values submitted through the API should exist beforehand. Otherwise the
field will not be saved.
