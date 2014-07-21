<?

require 'vendor/autoload.php';

$activityFacade = new \raynet\CreateComplexActivityFacade('instanceName', 'userAccountEmail', 'userAccountApiKey');
$activityFacade->createComplexActivityWithPersonOrCompanyContext(array(
    'deadline'          => '2014-09-01 00:00',
    'priority'          => 'DEFAULT',
    'title'             => 'Another activity title',
    'scheduledFrom'     => '2014-09-01 00:00',
    'scheduledTill'     => '2014-09-01 00:00'
), array(
    'contactInfo'           => array(
        'email'     => 'johndoe@sometestingaccount.com'
    ),
    'firstName'             => 'John',
    'lastName'              => 'Doe',
    'owner'                 => 2
), array(
    'name'          => 'Some testing account',
    'owner'         => 2,
    'state'         => 'A_POTENTIAL',
    'rating'        => 'B',
    'role'          => 'A_SUBSCRIBER',
    'addresses'     => array(
        array(
            'address'   => array(
                'name'      => 'Primary address'
            )
        )
    )
), 'An Expert');