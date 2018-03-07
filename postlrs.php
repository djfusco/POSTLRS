<?php

//Actor Information
$actorEmail = $_POST["actorEmail"];
$actorName  = $_POST["actorName"];

//Verb Information – ALWAYS the same
$verbIdURL   = 'http://adlnet.gov/expapi/verbs/completed';
$verbDisplay = 'completed';

//Activity Information
$activityId          = $_POST["activityId"];
$activityName        = $_POST["activityName"];
$activityDescription = $_POST["activityDescription"];
$activityAssignment  = 'http://adlnet.gov/expapi/activity/'.$_POST["activityAssi
gnment"];
$activityTypeURL     = 'http://adlnet.gov/expapi/activities/media';

//Result Information
$resultScoreRaw         = $_POST["resultScoreRaw"];
$resultResponse      	= $_POST["resultResponse"];

//Fixed Values
$actorObjectType        = 'Agent';
$activityObjectType     = 'Activity';

//Add libraries
use TinCan\RemoteLRS;
use TinCan\Agent;
use TinCan\Verb;
use TinCan\Activity;
use TinCan\Statement;
use TinCan\Result;

require '/home/ubuntu/vendor/autoload.php';

//Build LRS Connection
$lrs = new TinCan\RemoteLRS(
  'http://LRS_ENDPOINT',
  '1.0.1',
  'UN',
  'PW'
);


//Build Actor
$actor = new TinCan\Agent(
    [ 'mbox' => "$actorEmail",
      'name' => "$actorName",
      'objectType' => "$actorObjectType"
    ]
);


//Build Verb
$verb = new TinCan\Verb(
    [ 'id' => "$verbIdURL",
      display =>
      [
                'en-US' => "$verbDisplay"
      ]
    ]
);


//Build Activity
$activity = new TinCan\Activity(
    [ 'id' => "$activityId",
      definition =>
      [
        name =>
        [
                'en-US' => "$activityName"
        ],
        description =>
        [
                'en-US' => "$activityDescription"
        ],
      'type'     => "$activityTypeURL",
      'moreInfo' => "$activityAssignment",
      ],
      'objectType' => "$activityObjectType"
    ]
);

//Build Result
$result = new TinCan\Result(
    [ 'score' =>
      	[
		'raw' =>"$resultScoreRaw"
	],
      'response' => "$resultResponse"
    ]
);

$statement = new TinCan\Statement(
    [
        'actor'   => $actor,
        'verb'    => $verb,
        'object'  => $activity,
        'result'  => $result
    ]
);


$response = $lrs->saveStatement($statement);
if ($response->success) {
    print "Statement sent successfully!\n";
}
else {
    print "Error statement not sent: " . $response->content . "\n";
}
?>

