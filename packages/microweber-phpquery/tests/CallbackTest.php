<?php

namespace MicroweberPackages\PhpQuery\Tests;

use MicroweberPackages\PhpQuery\Callbacks\Callback;
use MicroweberPackages\PhpQuery\Callbacks\CallbackReturnValue;
use MicroweberPackages\PhpQuery\Callbacks\CallbackReturnReference;
use MicroweberPackages\PhpQuery\Callbacks\CallbackParameterToReference;
use MicroweberPackages\PhpQuery\Callbacks\CallbackParam;
use MicroweberPackages\PhpQuery\Callbacks\ICallbackNamed;
use MicroweberPackages\PhpQuery\PhpQuery;

class CallbackTest extends TestCase
{
    public function test_callback_creation()
    {
        $cb = new Callback('strtoupper');
        $this->assertEquals('strtoupper', $cb->callback);
    }

    public function test_callback_with_params()
    {
        $cb = new Callback('substr', 0, 5);
        $this->assertEquals('substr', $cb->callback);
        $this->assertCount(2, $cb->params);
    }

    public function test_callback_implements_icallbacknamed()
    {
        $cb = new Callback('strtoupper');
        $this->assertInstanceOf(ICallbackNamed::class, $cb);
    }

    public function test_callback_set_name()
    {
        $cb = new Callback('strtoupper');
        $cb->setName('myCallback');
        $this->assertTrue($cb->hasName());
        $this->assertEquals('Callback: myCallback', $cb->getName());
    }

    public function test_callback_no_name()
    {
        $cb = new Callback('strtoupper');
        $this->assertFalse($cb->hasName());
    }

    public function test_callback_return_value()
    {
        $cb = new CallbackReturnValue('hello', 'testCb');
        $this->assertEquals('hello', $cb->callback());
        $this->assertTrue($cb->hasName());
        $this->assertEquals('Callback: testCb', (string) $cb);
    }

    public function test_callback_return_reference()
    {
        $ref = 'original';
        $cb = new CallbackReturnReference($ref);
        $this->assertEquals('original', $cb->callback());

        $ref = 'modified';
        $this->assertEquals('modified', $cb->callback());
    }

    public function test_callback_parameter_to_reference()
    {
        $ref = null;
        $cb = new CallbackParameterToReference($ref);
        $this->assertNull($cb->callback);
    }

    public function test_callback_param_class_exists()
    {
        $param = new CallbackParam();
        $this->assertInstanceOf(CallbackParam::class, $param);
    }

    public function test_callback_run_simple()
    {
        $result = PhpQuery::callbackRun('strtoupper', ['hello']);
        $this->assertEquals('HELLO', $result);
    }

    public function test_callback_run_with_callback_object()
    {
        $cb = new Callback('strtoupper');
        $result = PhpQuery::callbackRun($cb, ['hello']);
        $this->assertEquals('HELLO', $result);
    }

    public function test_callback_run_with_null()
    {
        $result = PhpQuery::callbackRun(null, ['hello']);
        $this->assertNull($result);
    }
}