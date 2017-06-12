<?php
$I = new ApiTester($scenario);
$I->wantToTest('user data api');

$I->sendGET('/users');
$I->seeResponseCodeIs(401);

$I->sendPOST('/auth', [
    'login' => 'user',
    'password' => 'password'
]);
$location = $I->grabHttpHeader('Location');
$locationParts = explode('/', $location);
$authKey = array_pop($locationParts);

$I->haveHttpHeader('Authorization', $authKey);
$I->sendGET('/users');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson([
    [
        'id' => 1,
        'login' => 'user'
    ]
]);
