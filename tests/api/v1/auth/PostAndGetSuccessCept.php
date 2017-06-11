<?php
$I = new ApiTester($scenario);
$I->wantToTest('. ');
$I->sendPOST('/auth', [
    'login' => 'user',
    'password' => 'password'
]);
$I->seeResponseCodeIs(201);
$I->seeHttpHeader('Location');
$I->seeResponseContainsJson([
    'user' => [
        'id' => 1,
        'login' => 'user',
    ],
]);

$location = $I->grabHttpHeader('Location');
$locationParts = explode('/', $location);
$authKey = array_pop($locationParts);
$I->sendGET($location);
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson(
    [
        'authKey' => $authKey,
        'user' => [
            'id' => 1,
            'login' => 'user',
        ],
    ]
);

$I->sendHEAD($location);
$I->seeResponseCodeIs(204);