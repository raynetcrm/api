<?php
include '../Raynetcrm.php';

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $instanceName = 'nazevinstance'; // name of your RAYNET CRM
    $userName = 'user@example.com'; // username
    $apiKey = 'api-key'; // API key (from Users' profile -> Change security -> Reset new API key)
    $crm = new Raynetcrm($instanceName, $userName, $apiKey);
    $data = array(
        'subject' => $_REQUEST['subject'],
        'priority' => $_REQUEST['priority'],
        'company' => $_REQUEST['company'],
        'firstName' => $_REQUEST['firstName'],
        'lastName' => $_REQUEST['lastName'],
        'source' => $_REQUEST['source'],
        'category' => $_REQUEST['category'],
        'note' => $_REQUEST['note'],
        'address' => array(
            'street' => $_REQUEST['street'],
            'city' => $_REQUEST['city'],
            'district' => $_REQUEST['district'],
            'postalCode' => $_REQUEST['postalCode'],
            'country' => $_REQUEST['country'],
            'businessRegion' => $_REQUEST['businessRegion']
        ),
        'contactInfo' => array(
            'firstPhone' => $_REQUEST['firstPhone'],
            'secondPhone' => $_REQUEST['secondPhone'],
            'firstPhoneType' => $_REQUEST['firstPhoneType'],
            'secondPhoneType' => $_REQUEST['secondPhoneType'],
            'email' => $_REQUEST['email'],
            'web' => $_REQUEST['web'],
            'fax' => $_REQUEST['fax'],
            'otherContacts' => $_REQUEST['otherContacts']
        )
    );

    $result = $crm->insertLead($data, array('notification-email@example.com'), 'Lead has been created via web form');
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">

    <title>RAYNET CRM API</title>
</head>
<body style="padding-bottom: 20px;">
    <div class="container"><div class="row"><div class="col-md-8">

        <h1>Create a new lead in RAYNET CRM</h1>

        <?php if ($result === true): ?>
            <div class="alert alert-success">Lead has been successfully created.</div>
        <?php elseif ($result === false):?>
            <div class="alert alert-danger">An error has occurred.</div>
        <?php endif; ?>

        <form role="form" method="post">
            <h2>Basic Information</h2>

            <div class="form-group">
                <label for="subject">Subject</label>
                <input class="form-control" type="text" name="subject" id="subject" />
            </div>

            <div class="form-group">
                <label for="priority">Priority</label>
                <select class="form-control" name="priority" id="priority">
                    <option value="MINOR">Low</option>
                    <option value="DEFAULT">Normal</option>
                    <option value="CRITICAL">High</option>
                </select>
            </div>

            <div class="form-group">
                <label for="company">Account</label>
                <input class="form-control" type="text" name="company" id="company" />
            </div>

            <div class="form-group">
                <label for="firstName">Name</label>
                <input class="form-control" type="text" name="firstName" id="firstName" />
            </div>

            <div class="form-group">
                <label for="lastName">Surname</label>
                <input class="form-control" type="text" name="lastName" id="lastName" />
            </div>

            <div class="form-group">
                <label for="source">Source</label>
                <select class="form-control" name="source" id="source">
                    <option value="call center">call center</option>
                    <option value="web form">web form</option>
                </select>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control" name="category" id="category">
                    <option value="food processing">food processing</option>
                    <option value="software development">software development</option>
                </select>
            </div>

            <div class="form-group">
                <label for="note">Note to Lead</label>
                <textarea class="form-control" name="note" id="name"></textarea>
            </div>

            <h2>Adresa</h2>

            <div class="form-group">
                <label for="street">Street</label>
                <input class="form-control" type="text" name="street" id="street" />
            </div>

            <div class="form-group">
                <label for="city">City</label>
                <input class="form-control" type="text" name="city" id="city" />
            </div>

            <div class="form-group">
                <label for="district">Region</label>
                <input class="form-control" type="text" name="district" id="district" />
            </div>

            <div class="form-group">
                <label for="postalCode">Postal code</label>
                <input class="form-control" type="text" name="postalCode" id="postalCode" />
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <select class="form-control" name="country" id="country">
                    <option value="US">United States</option>
                    <option value="CZ">Czech Republic</option>
                </select>
            </div>

            <div class="form-group">
                <label for="businessRegion">Business Territory</label>
                <select class="form-control" name="businessRegion" id="businessRegion">
                    <option value="World">World</option>
                    <option value="Europe">Europe</option>
                </select>
            </div>

            <h2>Contact Details</h2>

            <div class="form-group">
                <label for="firstPhone">Phone 1</label>
                <input class="form-control" type="text" name="firstPhone" id="firstPhone" />
            </div>

            <div class="form-group">
                <label for="firstPhoneType">Phone 1 - type</label>
                <select class="form-control" name="firstPhoneType" id="firstPhoneType">
                    <option value="cellphone">cellphone</option>
                    <option value="landline">landline</option>
                </select>
            </div>

            <div class="form-group">
                <label for="secondPhone">Phone 2</label>
                <input class="form-control" type="text" name="secondPhone" id="secondPhone" />
            </div>

            <div class="form-group">
                <label for="secondPhoneType">Phone 2 - type</label>
                <select class="form-control" name="secondPhoneType" id="secondPhoneType">
                    <option value="cellphone">cellphone</option>
                    <option value="landline">landline</option>
                </select>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" type="text" name="email" id="email" />
            </div>

            <div class="form-group">
                <label for="web">WWW</label>
                <input class="form-control" type="text" name="web" id="web" />
            </div>

            <div class="form-group">
                <label for="facebook">Facebook</label>
                <input class="form-control" type="text" name="facebook" id="facebook" />
            </div>

            <div class="form-group">
                <label for="fax">Fax</label>
                <input class="form-control" type="text" name="fax" id="fax" />
            </div>

            <div class="form-group">
                <label for="otherContacts">Other contacts</label>
                <textarea class="form-control" name="otherContacts" id="otherContacts"></textarea>
            </div>

            <button class="btn btn-primary">Create lead</button>
        </form>

    </div></div></div>
</body>
</html>
