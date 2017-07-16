<?php
$I = new ApiTester($scenario);
$I->wantToTest('user data api');

$I->sendGET('/users');
$I->seeResponseCodeIs(401);

$authKey = $I->getAuthKey('user', 'password');
$I->haveHttpHeader('Authorization', $authKey);
$I->sendGET('/users');
$I->seeResponseCodeIs(200);
$I->seeResponseContainsJson([
    '_links' => [
        'self' => [
            'href' => '/api/v1/users'
        ]
    ],
    '_embedded' => [
        // TODO after https://github.com/nilportugues/php-hal/issues/11
        'users' => [
            [
                'id' => 1,
                'login' => 'user'
            ],
        ]
    ],
    'total' => 1,
]);