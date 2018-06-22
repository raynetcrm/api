<?php

use Raynet\Client\RaynetCrmRestClient;
use Raynet\Client\RaynetGenericException;

require_once "../../vendor/autoload.php";

$instanceName = 'instanceName'; // name of your RAYNET CRM
$userName = 'userName'; // user name (email address)
$apiKey = 'apiKey'; // API key (from Application settings -> For developers -> API keys -> NEW API KEY)

$crm = new RaynetCrmRestClient($instanceName, $userName, $apiKey);
$result = null;
$resultMsg = null;

$territoryValues = $crm->getCodelistValues('territory');
$contactSourceValues = $crm->getCodelistValues('contactSource');
$leadCategoryValues = $crm->getCodelistValues('leadCategory');
$telTypeValues = $crm->getCodelistValues('telType');
$countryValues = $crm->getCodelistValues('country');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = array(
        'topic' => $_REQUEST['topic'],
        'priority' => $_REQUEST['priority'],
        'companyName' => $_REQUEST['companyName'],
        'firstName' => $_REQUEST['firstName'],
        'lastName' => $_REQUEST['lastName'],
        'contactSource' => $_REQUEST['contactSource'],
        'category' => $_REQUEST['category'],
        'notice' => $_REQUEST['notice'],
        'address' => array(
            'street' => $_REQUEST['street'],
            'city' => $_REQUEST['city'],
            'province' => $_REQUEST['province'],
            'zipCode' => $_REQUEST['zipCode'],
            'country' => $_REQUEST['country'],
        ),
        'territory' => $_REQUEST['territory'],
        'contactInfo' => array(
            'tel1' => $_REQUEST['tel1'],
            'tel2' => $_REQUEST['tel2'],
            'tel1Type' => $_REQUEST['tel1Type'],
            'tel2Type' => $_REQUEST['tel2Type'],
            'email' => $_REQUEST['email'],
            'www' => $_REQUEST['www'],
            'fax' => $_REQUEST['fax'],
            'otherContact' => $_REQUEST['otherContact']
        ),
        'notificationEmailAddresses' => array('email@domain.com'),
        'notificationMessage' => 'Lead has been created via web form'
    );

    try {
        $result = $crm->createLead($data);
    } catch (RaynetGenericException $e) {
        $result = -1;
        $resultMsg = $e->getMessage();
    }
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

        <?php if ($result !== null && $result > 0): ?>
            <div class="alert alert-success">Lead has been successfully created.</div>
        <?php elseif ($result !== null && $result < 0):?>
            <div class="alert alert-danger">An error has occurred: <div><?php echo $resultMsg; ?>.</div></div>
        <?php endif; ?>

        <form role="form" method="post">
            <h2>Basic Information</h2>

            <div class="form-group">
                <label for="topic">Subject</label>
                <input class="form-control" type="text" name="topic" id="topic" />
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
                <label for="companyName">Account</label>
                <input class="form-control" type="text" name="companyName" id="companyName" />
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
                <label for="contactSource">Source</label>
                <select class="form-control" name="contactSource" id="contactSource">
                    <?php foreach($contactSourceValues as $key => $value) { ?>
                    <option value="<?php echo $value->id; ?>"><?php echo $value->code01; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select class="form-control" name="category" id="category">
                    <?php foreach($leadCategoryValues as $key => $value) { ?>
                    <option value="<?php echo $value->id; ?>"><?php echo $value->code01; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="notice">Note to Lead</label>
                <textarea class="form-control" name="notice" id="notice"></textarea>
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
                <label for="province">Region</label>
                <input class="form-control" type="text" name="province" id="province" />
            </div>

            <div class="form-group">
                <label for="zipCode">ZIP Code</label>
                <input class="form-control" type="text" name="zipCode" id="zipCode" />
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <select class="form-control" name="country" id="country">
                    <?php foreach($countryValues as $key => $value) { ?>
                    <option value="<?php echo $value->code; ?>"><?php echo $value->name; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="territory">Business Territory</label>
                <select class="form-control" name="territory" id="territory">
                    <?php foreach($territoryValues as $key => $value) { ?>
                    <option value="<?php echo $value->id; ?>"><?php echo $value->code01; ?></option>
                    <?php } ?>
                </select>
            </div>

            <h2>Contact Details</h2>

            <div class="form-group">
                <label for="tel1">Phone 1</label>
                <input class="form-control" type="text" name="tel1" id="tel1" />
            </div>

            <div class="form-group">
                <label for="tel1Type">Phone 1 - type</label>
                <select class="form-control" name="tel1Type" id="tel1Type">
                    <?php foreach($telTypeValues as $key => $value) { ?>
                    <option value="<?php echo $value->code01; ?>"><?php echo $value->code01; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="tel2">Phone 2</label>
                <input class="form-control" type="text" name="tel2" id="tel2" />
            </div>

            <div class="form-group">
                <label for="tel2Type">Phone 2 - type</label>
                <select class="form-control" name="tel2Type" id="tel2Type">
                    <?php foreach($telTypeValues as $key => $value) { ?>
                    <option value="<?php echo $value->code01; ?>"><?php echo $value->code01; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" type="text" name="email" id="email" />
            </div>

            <div class="form-group">
                <label for="www">WWW</label>
                <input class="form-control" type="text" name="www" id="www" />
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
                <label for="otherContact">Other contacts</label>
                <textarea class="form-control" name="otherContact" id="otherContact"></textarea>
            </div>

            <button class="btn btn-primary">Create lead</button>
        </form>

    </div></div></div>
</body>
</html>
