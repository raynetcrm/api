<?php

use Raynet\Client\RaynetGenericException;
use Raynet\ExampleInsertActivity\CreateComplexActivityFacade;

require_once "../../vendor/autoload.php";

$instanceName = 'instanceName'; // name of your RAYNET CRM
$userName = 'userName'; // user name (email address)
$apiKey = 'apiKey'; // API key (from Application settings -> For developers -> API keys -> NEW API KEY)

$resultCode = null;
$resultMsg = null;

try {
    $crm = new CreateComplexActivityFacade($instanceName, $userName, $apiKey);
    $categoryValues = $crm->getCodelistValues('activityCategory');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $activityData = array(
            'deadline'          => $_REQUEST['deadline'],
            'priority'          => $_REQUEST['priority'],
            'title'             => $_REQUEST['title'],
            'category'          => $_REQUEST['category'],
            'scheduledFrom'     => $_REQUEST['scheduledFrom'],
            'scheduledTill'     => $_REQUEST['scheduledTill'],
            'description'       => $_REQUEST['description'],
            'owner'             => 2,
            'resolver'          => 2
        );

        $accountData = array(
            'name'       => $_REQUEST['accountName'],
            'owner'      => 2,
            'state'      => 'A_POTENTIAL',
            'role'       => 'A_SUBSCRIBER',
            'rating'     => 'C',
            'addresses'  => array(
                array(
                    'address' => array(
                        'name' => 'Company Headquarters'
                    )
                )
            ),
        );

        $personData = array(
            'firstName'   => $_REQUEST['firstName'],
            'lastName'    => $_REQUEST['lastName'],
            'owner'       => 2,
            'contactInfo' => array(
                'email' => $_REQUEST['personEmail']
            )
        );

        $position = $_REQUEST['personPosition'];

        $resultCode = $crm->createComplexTaskWithPersonOrCompanyContext($activityData, $personData, $accountData, $position);
    }
} catch (RaynetGenericException $e) {
    $resultCode = $e->getCode();
    $resultMsg = $e->getMessage();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>

    <title>RAYNET CRM API</title>
</head>
<body style="padding-bottom: 20px;">
    <div class="container"><div class="row"><div class="col-md-8">

        <h1>Create a new task in RAYNET CRM</h1>

        <?php if ($resultCode !== null && $resultCode <= 201): ?>
            <div class="alert alert-success">Task has been successfully created.</div>
        <?php elseif ($resultCode !== null && $resultCode >= 400):?>
            <div class="alert alert-danger">Error <?php echo $resultCode; ?>: <div><?php echo $resultMsg; ?></div></div>
        <?php endif; ?>

        <form role="form" method="post">
            <h2>Basic Information</h2>

            <div class="form-group">
                <label for="title">Title *</label>
                <input class="form-control" type="text" name="title" id="title" required/>
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
                <label for="category">Category</label>
                <select class="form-control" name="category" id="category">
                    <?php foreach($categoryValues as $key => $value) { ?>
                        <option value="<?php echo $value->id; ?>"><?php echo $value->code01; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="scheduledFrom">Scheduled from</label>
                <div class="input-group date" id="scheduledFrom-picker">
                    <input type="text" name="scheduledFrom" id="scheduledFrom" class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label for="scheduledTill">Scheduled till</label>
                <div class="input-group date" id="scheduledTill-picker">
                    <input type="text" name="scheduledTill" id="scheduledTill" class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label for="deadline">Deadline *</label>
                <div class="input-group date" id="deadline-picker">
                    <input type="text" name="deadline" id="deadline" class="form-control" required/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Task description</label>
                <textarea class="form-control" name="description" id="description"></textarea>
            </div>

            <h2>Account</h2>
            <div class="form-group">
                <label for="accountName">Name</label>
                <input class="form-control" type="text" name="accountName" id="accountName" />
            </div>

            <h2>Person</h2>
            <div class="form-group">
                <label for="firstName">Name</label>
                <input class="form-control" type="text" name="firstName" id="firstName" />
            </div>

            <div class="form-group">
                <label for="lastName">Surname</label>
                <input class="form-control" type="text" name="lastName" id="lastName" />
            </div>

            <div class="form-group">
                <label for="personEmail">Email</label>
                <input class="form-control" type="text" name="personEmail" id="personEmail" />
            </div>

            <div class="form-group">
                <label for="personPosition">Position in the company</label>
                <input class="form-control" type="text" name="personPosition" id="personPosition" />
            </div>

            <button class="btn btn-primary">Create task</button>
        </form>

    </div></div></div>

    <script type="text/javascript">
        $(document).ready(function() {
            $(function() {
                var _format = 'YYYY-MM-DD HH:mm';
                $('#scheduledFrom-picker').datetimepicker({format: _format});
                $('#scheduledTill-picker').datetimepicker({format: _format});
                $('#deadline-picker').datetimepicker({format: _format});
            });
        });
    </script>
</body>
</html>
