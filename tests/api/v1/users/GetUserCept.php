<?php
$I = new ApiTester($scenario);
$I->wantToTest('user data api');

$I->sendGET('/users/1');
$I->seeResponseCodeIs(401);

$authKey = $I->getAuthKey('user', 'password');
$I->haveHttpHeader('Authorization', $authKey);
$I->sendGET('/users/1');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson([
    '_links' => [
        'self' => [
            'href' => '/api/v1/users/1'
        ],
    ],
    'id' => 1,
    'login' => 'user'
]);