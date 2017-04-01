<?php

namespace FreeElephants\HexammonServerTests;

use PHPUnit_Framework_MockObject_Matcher_InvokedRecorder;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getFirstInvocationFirstArgument(PHPUnit_Framework_MockObject_Matcher_InvokedRecorder $spy)
    {
        return $spy->getInvocations()[0]->parameters[0];
    }
}