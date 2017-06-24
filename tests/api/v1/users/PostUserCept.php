<?php
$I = new ApiTester($scenario);
$I->wantToTest('user registration api');

$I->sendPOST('/users', [
    'login' => 'foo',
    'password' => 'password',
    'email' => 'foo@bar',
    'invite' => '123456'
]);
$I->seeResponseCodeIs(201);
$I->seeHttpHeader('Location', '/api/v1/users/2');
$I->seeResponseContainsJson([
    'id' => 2,
    'login' => 'foo',
]);