<?php
use Valitron\Validator;

class ValidateTest extends BaseTestCase
{
    public function testValidWithNoRules()
    {
        $v = new Validator(array('name' => 'Chester Tester'));
        $this->assertTrue($v->validate());
    }

    public function testOptionalFieldFilter()
    {
        $v = new Validator(array('foo' => 'bar', 'bar' => 'baz'), array('foo'));
        $this->assertEquals($v->data(), array('foo' => 'bar'));
    }

    public function testAccurateErrorCount()
    {
        $v = new Validator(array('name' => 'Chester Tester'));
        $v->rule('required', 'name');
        $this->assertSame(1, count($v->errors('name')));
    }
    public function testArrayOfFieldsToValidate()
    {
        $v = new Validator(array('name' => 'Chester Tester', 'email' => 'chester@tester.com'));
        $v->rule('required', array('name', 'email'));
        $this->assertTrue($v->validate());
    }

    public function testArrayOfFieldsToValidateOneEmpty()
    {
        $v = new Validator(array('name' => 'Chester Tester', 'email' => ''));
        $v->rule('required', array('name', 'email'));
        $this->assertFalse($v->validate());
    }

    public function testRequiredValid()
    {
        $v = new Validator(array('name' => 'Chester Tester'));
        $v->rule('required', 'name');
        $this->assertTrue($v->validate());
    }

    public function testRequiredNonExistentField()
    {
        $v = new Validator(array('name' => 'Chester Tester'));
        $v->rule('required', 'nonexistent_field');
        $this->assertFalse($v->validate());
    }

    public function testEqualsValid()
    {
        $v = new Validator(array('foo' => 'bar', 'bar' => 'bar'));
        $v->rule('equals', 'foo', 'bar');
        $this->assertTrue($v->validate());
    }

    public function testEqualsInvalid()
    {
        $v = new Validator(array('foo' => 'foo', 'bar' => 'bar'));
        $v->rule('equals', 'foo', 'bar');
        $this->assertFalse($v->validate());
    }

    public function testDifferentValid()
    {
        $v = new Validator(array('foo' => 'bar', 'bar' => 'baz'));
        $v->rule('different', 'foo', 'bar');
        $this->assertTrue($v->validate());
    }

    public function testDifferentInvalid()
    {
        $v = new Validator(array('foo' => 'baz', 'bar' => 'baz'));
        $v->rule('different', 'foo', 'bar');
        $this->assertFalse($v->validate());
    }

    public function testAcceptedValid()
    {
        $v = new Validator(array('agree' => 'yes'));
        $v->rule('accepted', 'agree');
        $this->assertTrue($v->validate());
    }

    public function testAcceptedInvalid()
    {
        $v = new Validator(array('agree' => 'no'));
        $v->rule('accepted', 'agree');
        $this->assertFalse($v->validate());
    }

    public function testNumericValid()
    {
        $v = new Validator(array('num' => '42.341569'));
        $v->rule('numeric', 'num');
        $this->assertTrue($v->validate());
    }

    public function testNumericInvalid()
    {
        $v = new Validator(array('num' => 'nope'));
        $v->rule('numeric', 'num');
        $this->assertFalse($v->validate());
    }

    public function testIntegerValid()
    {
        $v = new Validator(array('num' => '41243'));
        $v->rule('integer', 'num');
        $this->assertTrue($v->validate());
    }

    public function testIntegerInvalid()
    {
        $v = new Validator(array('num' => '42.341569'));
        $v->rule('integer', 'num');
        $this->assertFalse($v->validate());
    }

    public function testLengthValid()
    {
        $v = new Validator(array('str' => 'happy'));
        $v->rule('length', 'str', 5);
        $this->assertTrue($v->validate());
    }

    public function testLengthInvalid()
    {
        $v = new Validator(array('str' => 'sad'));
        $v->rule('length', 'str', 6);
        $this->assertFalse($v->validate());
    }

    public function testLengthBetweenValid()
    {
        $v = new Validator(array('str' => 'happy'));
        $v->rule('lengthBetween', 'str', 2, 8);
        $this->assertTrue($v->validate());
    }

    public function testLengthBetweenInvalid()
    {
        $v = new Validator(array('str' => 'sad'));
        $v->rule('lengthBetween', 'str', 4, 10);
        $this->assertFalse($v->validate());
    }

    public function testLengthMinValid()
    {
        $v = new Validator(array('str' => 'happy'));
        $v->rule('lengthMin', 'str', 4);
        $this->assertTrue($v->validate());
    }

    public function testLengthMinInvalid()
    {
        $v = new Validator(array('str' => 'sad'));
        $v->rule('lengthMin', 'str', 4);
        $this->assertFalse($v->validate());
    }

    public function testLengthMaxValid()
    {
        $v = new Validator(array('str' => 'sad'));
        $v->rule('lengthMax', 'str', 4);
        $this->assertTrue($v->validate());
    }

    public function testLengthMaxInvalid()
    {
        $v = new Validator(array('str' => 'sad'));
        $v->rule('lengthMax', 'str', 2);
        $this->assertFalse($v->validate());
    }

    public function testMinValid()
    {
        $v = new Validator(array('num' => 5));
        $v->rule('min', 'num', 2);
        $this->assertTrue($v->validate());

        $v = new Validator(array('num' => 5));
        $v->rule('min', 'num', 5);
        $this->assertTrue($v->validate());
    }

    public function testMinValidFloat()
    {
        $v = new Validator(array('num' => 0.9));
        $v->rule('min', 'num', 0.5);
        $this->assertTrue($v->validate());

        $v = new Validator(array('num' => 1 - 0.81));
        $v->rule('min', 'num', 0.19);
        $this->assertTrue($v->validate());
    }

    public function testMinInvalid()
    {
        $v = new Validator(array('num' => 5));
        $v->rule('min', 'num', 6);
        $this->assertFalse($v->validate());
    }

    public function testMinInvalidFloat()
    {
        $v = new Validator(array('num' => 0.5));
        $v->rule('min', 'num', 0.9);
        $this->assertFalse($v->validate());
    }

    public function testMaxValid()
    {
        $v = new Validator(array('num' => 5));
        $v->rule('max', 'num', 6);
        $this->assertTrue($v->validate());

        $v = new Validator(array('num' => 5));
        $v->rule('max', 'num', 5);
        $this->assertTrue($v->validate());
    }

    public function testMaxValidFloat()
    {
        $v = new Validator(array('num' => 0.4));
        $v->rule('max', 'num', 0.5);
        $this->assertTrue($v->validate());

        $v = new Validator(array('num' => 1 - 0.83));
        $v->rule('max', 'num', 0.17);
        $this->assertTrue($v->validate());
    }

    public function testMaxInvalid()
    {
        $v = new Validator(array('num' => 5));
        $v->rule('max', 'num', 4);
        $this->assertFalse($v->validate());
    }

    public function testMaxInvalidFloat()
    {
        $v = new Validator(array('num' => 0.9));
        $v->rule('max', 'num', 0.5);
        $this->assertFalse($v->validate());
    }

    public function testInValid()
    {
        $v = new Validator(array('color' => 'green'));
        $v->rule('in', 'color', array('red', 'green', 'blue'));
        $this->assertTrue($v->validate());
    }

    public function testInValidAssociativeArray()
    {
        $v = new Validator(array('color' => 'green'));
        $v->rule('in', 'color', array(
            'red' => 'Red',
            'green' => 'Green',
            'blue' => 'Blue'
        ));
        $this->assertTrue($v->validate());
    }

    public function testInInvalid()
    {
        $v = new Validator(array('color' => 'yellow'));
        $v->rule('in', 'color', array('red', 'green', 'blue'));
        $this->assertFalse($v->validate());
    }

    public function testNotInValid()
    {
        $v = new Validator(array('color' => 'yellow'));
        $v->rule('notIn', 'color', array('red', 'green', 'blue'));
        $this->assertTrue($v->validate());
    }

    public function testNotInInvalid()
    {
        $v = new Validator(array('color' => 'blue'));
        $v->rule('notIn', 'color', array('red', 'green', 'blue'));
        $this->assertFalse($v->validate());
    }

    public function testIpValid()
    {
        $v = new Validator(array('ip' => '127.0.0.1'));
        $v->rule('ip', 'ip');
        $this->assertTrue($v->validate());
    }

    public function testIpInvalid()
    {
        $v = new Validator(array('ip' => 'buy viagra now!'));
        $v->rule('ip', 'ip');
        $this->assertFalse($v->validate());
    }

    public function testEmailValid()
    {
        $v = new Validator(array('name' => 'Chester Tester', 'email' => 'chester@tester.com'));
        $v->rule('email', 'email');
        $this->assertTrue($v->validate());
    }

    public function testEmailInvalid()
    {
        $v = new Validator(array('name' => 'Chester Tester', 'email' => 'chestertesterman'));
        $v->rule('email', 'email');
        $this->assertFalse($v->validate());
    }

    public function testUrlValid()
    {
        $v = new Validator(array('website' => 'http://google.com'));
        $v->rule('url', 'website');
        $this->assertTrue($v->validate());
    }

    public function testUrlInvalid()
    {
        $v = new Validator(array('website' => 'shoobedobop'));
        $v->rule('url', 'website');
        $this->assertFalse($v->validate());
    }

    public function testUrlActive()
    {
        $v = new Validator(array('website' => 'http://google.com'));
        $v->rule('urlActive', 'website');
        $this->assertTrue($v->validate());
    }

    public function testUrlInactive()
    {
        $v = new Validator(array('website' => 'http://sonotgoogleitsnotevenfunny.dev'));
        $v->rule('urlActive', 'website');
        $this->assertFalse($v->validate());
    }

    public function testAlphaValid()
    {
        $v = new Validator(array('test' => 'abcDEF'));
        $v->rule('alpha', 'test');
        $this->assertTrue($v->validate());
    }

    public function testAlphaInvalid()
    {
        $v = new Validator(array('test' => 'abc123'));
        $v->rule('alpha', 'test');
        $this->assertFalse($v->validate());
    }

    public function testAlphaNumValid()
    {
        $v = new Validator(array('test' => 'abc123'));
        $v->rule('alphaNum', 'test');
        $this->assertTrue($v->validate());
    }

    public function testAlphaNumInvalid()
    {
        $v = new Validator(array('test' => 'abc123$%^'));
        $v->rule('alphaNum', 'test');
        $this->assertFalse($v->validate());
    }

    public function testAlphaDashValid()
    {
        $v = new Validator(array('test' => 'abc-123_DEF'));
        $v->rule('slug', 'test');
        $this->assertTrue($v->validate());
    }

    public function testAlphaDashInvalid()
    {
        $v = new Validator(array('test' => 'abc-123_DEF $%^'));
        $v->rule('slug', 'test');
        $this->assertFalse($v->validate());
    }

    public function testRegexValid()
    {
        $v = new Validator(array('test' => '42'));
        $v->rule('regex', 'test', '/[\d]+/');
        $this->assertTrue($v->validate());
    }

    public function testRegexInvalid()
    {
        $v = new Validator(array('test' => 'istheanswer'));
        $v->rule('regex', 'test', '/[\d]+/');
        $this->assertFalse($v->validate());
    }

    public function testDateValid()
    {
        $v = new Validator(array('date' => '2013-01-27'));
        $v->rule('date', 'date');
        $this->assertTrue($v->validate());
    }

    public function testDateInvalid()
    {
        $v = new Validator(array('date' => 'no thanks'));
        $v->rule('date', 'date');
        $this->assertFalse($v->validate());
    }

    /**
     * @group issue-13
     */
    public function testDateValidWhenEmptyButNotRequired()
    {
        $v = new Validator(array('date' => ''));
        $v->rule('date', 'date');
        $this->assertTrue($v->validate());
    }

    public function testDateFormatValid()
    {
        $v = new Validator(array('date' => '2013-01-27'));
        $v->rule('dateFormat', 'date', 'Y-m-d');
        $this->assertTrue($v->validate());
    }

    public function testDateFormatInvalid()
    {
        $v = new Validator(array('date' => 'no thanks'));
        $v->rule('dateFormat', 'date', 'Y-m-d');
        $this->assertFalse($v->validate());
    }

    public function testDateBeforeValid()
    {
        $v = new Validator(array('date' => '2013-01-27'));
        $v->rule('dateBefore', 'date', new \DateTime('2013-01-28'));
        $this->assertTrue($v->validate());
    }

    public function testDateBeforeInvalid()
    {
        $v = new Validator(array('date' => '2013-01-27'));
        $v->rule('dateBefore', 'date', '2013-01-26');
        $this->assertFalse($v->validate());
    }

    public function testDateAfterValid()
    {
        $v = new Validator(array('date' => '2013-01-27'));
        $v->rule('dateAfter', 'date', new \DateTime('2013-01-26'));
        $this->assertTrue($v->validate());
    }

    public function testDateAfterInvalid()
    {
        $v = new Validator(array('date' => '2013-01-27'));
        $v->rule('dateAfter', 'date', '2013-01-28');
        $this->assertFalse($v->validate());
    }

    public function testContainsValid()
    {
        $v = new Validator(array('test_string' => 'this is a test'));
        $v->rule('contains', 'test_string', 'a test');
        $this->assertTrue($v->validate());
    }

    public function testContainsNotFound()
    {
        $v = new Validator(array('test_string' => 'this is a test'));
        $v->rule('contains', 'test_string', 'foobar');
        $this->assertFalse($v->validate());
    }

    public function testContainsInvalidValue()
    {
        $v = new Validator(array('test_string' => 'this is a test'));
        $v->rule('contains', 'test_string', array('test'));
        $this->assertFalse($v->validate());
    }

    public function testAcceptBulkRulesWithSingleParams()
    {
        $rules = array(
            'required' => 'nonexistent_field',
            'accepted' => 'foo',
            'integer' =>  'foo'
        );

        $v1 = new Validator(array('foo' => 'bar', 'bar' => 'baz'));
        $v1->rules($rules);
        $v1->validate();

        $v2 = new Validator(array('foo' => 'bar', 'bar' => 'baz'));
        $v2->rule('required', 'nonexistent_field');
        $v2->rule('accepted', 'foo');
        $v2->rule('integer', 'foo');
        $v2->validate();

        $this->assertEquals($v1->errors(), $v2->errors());
    }

    public function testAcceptBulkRulesWithMultipleParams()
    {
        $rules = array(
            'required' => array(
                array(array('nonexistent_field', 'other_missing_field'))
            ),
            'equals' => array(
                array('foo', 'bar')
            ),
            'length' => array(
                array('foo', 5)
            )
        );

        $v1 = new Validator(array('foo' => 'bar', 'bar' => 'baz'));
        $v1->rules($rules);
        $v1->validate();

        $v2 = new Validator(array('foo' => 'bar', 'bar' => 'baz'));
        $v2->rule('required', array('nonexistent_field', 'other_missing_field'));
        $v2->rule('equals', 'foo', 'bar');
        $v2->rule('length', 'foo', 5);
        $v2->validate();

        $this->assertEquals($v1->errors(), $v2->errors());
    }

    public function testAcceptBulkRulesWithNestedRules()
    {
        $rules = array(
            'length'   => array(
                array('foo', 5),
                array('bar', 5)
            )
        );

        $v1 = new Validator(array('foo' => 'bar', 'bar' => 'baz'));
        $v1->rules($rules);
        $v1->validate();

        $v2 = new Validator(array('foo' => 'bar', 'bar' => 'baz'));
        $v2->rule('length', 'foo', 5);
        $v2->rule('length', 'bar', 5);
        $v2->validate();

        $this->assertEquals($v1->errors(), $v2->errors());
    }

    public function testAcceptBulkRulesWithNestedRulesAndMultipleFields()
    {
        $rules = array(
            'length'   => array(
                array(array('foo', 'bar'), 5),
                array('baz', 5)
            )
        );

        $v1 = new Validator(array('foo' => 'bar', 'bar' => 'baz', 'baz' => 'foo'));
        $v1->rules($rules);
        $v1->validate();

        $v2 = new Validator(array('foo' => 'bar', 'bar' => 'baz', 'baz' => 'foo'));
        $v2->rule('length', array('foo', 'bar'), 5);
        $v2->rule('length', 'baz', 5);
        $v2->validate();

        $this->assertEquals($v1->errors(), $v2->errors());
    }

    public function testAcceptBulkRulesWithMultipleArrayParams()
    {
        $rules = array(
            'in'   => array(
                array(array('foo', 'bar'), array('x', 'y'))
            )
        );

        $v1 = new Validator(array('foo' => 'bar', 'bar' => 'baz', 'baz' => 'foo'));
        $v1->rules($rules);
        $v1->validate();

        $v2 = new Validator(array('foo' => 'bar', 'bar' => 'baz', 'baz' => 'foo'));
        $v2->rule('in', array('foo', 'bar'), array('x', 'y'));
        $v2->validate();

        $this->assertEquals($v1->errors(), $v2->errors());
    }

    public function testCustomLabelInMessage()
    {
        $v = new Valitron\Validator(array());
        $v->rule('required', 'name')->message('{field} is required')->label('NAME!!!');
        $v->validate();
        $this->assertEquals(array('NAME!!! is required'), $v->errors('name'));
    }

    public function testCustomLabelArrayInMessage()
    {
        $v = new Valitron\Validator(array());
        $v->rule('required', array('name', 'email'))->message('{field} is required');
        $v->labels(array(
          'name' => 'Name',
          'email' => 'Email address'
        ));
        $v->validate();
        $this->assertEquals(array(
          'name' => array('Name is required'),
          'email' => array('Email address is required')
        ), $v->errors());
    }

    public function testCustomLabelArrayWithoutMessage()
    {
        $v = new Valitron\Validator(array(
          'password' => 'foo',
          'passwordConfirm' => 'bar'
        ));
        $v->rule('equals', 'password', 'passwordConfirm');
        $v->labels(array(
          'password' => 'Password',
          'passwordConfirm' => 'Password Confirm'
        ));
        $v->validate();
        $this->assertEquals(array(
          'password' => array("Password must be the same as 'Password Confirm'"),
        ), $v->errors());
    }

    /**
     * Custom rules and callbacks
     */

    public function testAddRuleClosure()
    {
        $v = new Validator(array('name' => 'Chester Tester'));
        $v->addRule('testRule', function() { return true; });
        $v->rule('testRule', 'name');
        $this->assertTrue($v->validate());
    }

    public function testAddRuleClosureReturnsFalse()
    {
        $v = new Validator(array('name' => 'Chester Tester'));
        $v->addRule('testRule', function() { return false; });
        $v->rule('testRule', 'name');
        $this->assertFalse($v->validate());
    }

    public function testAddRuleClosureWithFieldArray()
    {
        $v = new Validator(array('name' => 'Chester Tester', 'email' => 'foo@example.com'));
        $v->addRule('testRule', function() { return true; });
        $v->rule('testRule', array('name', 'email'));
        $this->assertTrue($v->validate());
    }

    public function testAddRuleClosureWithArrayAsExtraParameter()
    {
        $v = new Validator(array('name' => 'Chester Tester'));
        $v->addRule('testRule', function() { return true; });
        $v->rule('testRule', 'name', array('foo', 'bar'));
        $this->assertTrue($v->validate());
    }

    public function testAddRuleCallback()
    {
        $v = new Validator(array('name' => 'Chester Tester'));
        $v->addRule('testRule', 'sampleFunctionCallback');
        $v->rule('testRule', 'name');
        $this->assertTrue($v->validate());
    }

    public function sampleObjectCallback() { return true; }
    public function sampleObjectCallbackFalse() { return false; }
    public function testAddRuleCallbackArray()
    {
        $v = new Validator(array('name' => 'Chester Tester'));
        $v->addRule('testRule', array($this, 'sampleObjectCallback'));
        $v->rule('testRule', 'name');
        $this->assertTrue($v->validate());
    }

    public function testAddRuleCallbackArrayWithArrayAsExtraParameter()
    {
        $v = new Validator(array('name' => 'Chester Tester'));
        $v->addRule('testRule', array($this, 'sampleObjectCallback'));
        $v->rule('testRule', 'name', array('foo', 'bar'));
        $this->assertTrue($v->validate());
    }

    public function testAddRuleCallbackArrayWithArrayAsExtraParameterAndCustomMessage()
    {
        $v = new Validator(array('name' => 'Chester Tester'));
        $v->addRule('testRule', array($this, 'sampleObjectCallbackFalse'));
        $v->rule('testRule', 'name', array('foo', 'bar'))->message('Invalid name selected.');
        $this->assertFalse($v->validate());
    }

    public function testAddRuleCallbackArrayWithArrayAsExtraParameterAndCustomMessageLabel()
    {
        $v = new Validator(array('name' => 'Chester Tester'));
        $v->labels(array('name' => 'Name'));
        $v->addRule('testRule', array($this, 'sampleObjectCallbackFalse'));
        $v->rule('testRule', 'name', array('foo', 'bar'))->message('Invalid name selected.');
        $this->assertFalse($v->validate());
    }

    public function testBooleanValid()
    {
        $v = new Validator(array('test' => true));
        $v->rule('boolean', 'test');
        $this->assertTrue($v->validate());
    }

    public function testBooleanInvalid()
    {
        $v = new Validator(array('test' => 'true'));
        $v->rule('boolean', 'test');
        $this->assertFalse($v->validate());
    }

    public function testZeroStillTriggersValidation()
    {
        $v = new Validator(array('test' => 0));
        $v->rule('min', 'test', 1);
        $this->assertFalse($v->validate());
    }

    public function testFalseStillTriggersValidation()
    {
        $v = new Validator(array('test' => FALSE));
        $v->rule('min', 'test', 5);
        $this->assertFalse($v->validate());
    }

    public function testCreditCardValid()
    {
        $visa         = array(4539511619543489, 4532949059629052, 4024007171194938, 4929646403373269, 4539135861690622);
        $mastercard   = array(5162057048081965, 5382687859049349, 5484388880142230, 5464941521226434, 5473481232685965);
        $amex         = array(371442067262027, 340743030537918, 345509167493596, 343665795576848, 346087552944316);
        $dinersclub   = array(30363194756249, 30160097740704, 38186521192206, 38977384214552, 38563220301454);
        $discover     = array(6011712400392605, 6011536340491809, 6011785775263015, 6011984124619056, 6011320958064251);

        foreach (compact('visa', 'mastercard', 'amex', 'dinersclub', 'discover') as $type => $numbers) {
            foreach($numbers as $number) {
                $v = new Validator(array('test' => $number));
                $v->rule('creditCard', 'test');
                $this->assertTrue($v->validate());
                $v->rule('creditCard', 'test', array($type, 'mastercard', 'visa'));
                $this->assertTrue($v->validate());
                $v->rule('creditCard', 'test', $type);
                $this->assertTrue($v->validate());
                $v->rule('creditCard', 'test', $type, array($type, 'mastercard', 'visa'));
                $this->assertTrue($v->validate());
                unset($v);
            }
        }
    }

    public function testcreditCardInvalid()
    {
        $visa         = array(3539511619543489, 3532949059629052, 3024007171194938, 3929646403373269, 3539135861690622);
        $mastercard   = array(4162057048081965, 4382687859049349, 4484388880142230, 4464941521226434, 4473481232685965);
        $amex         = array(271442067262027, 240743030537918, 245509167493596, 243665795576848, 246087552944316);
        $dinersclub   = array(20363194756249, 20160097740704, 28186521192206, 28977384214552, 28563220301454);
        $discover     = array(5011712400392605, 5011536340491809, 5011785775263015, 5011984124619056, 5011320958064251);

        foreach (compact('visa', 'mastercard', 'amex', 'dinersclub', 'discover') as $type => $numbers) {
            foreach($numbers as $number) {
                $v = new Validator(array('test' => $number));
                $v->rule('creditCard', 'test');
                $this->assertFalse($v->validate());
                $v->rule('creditCard', 'test', array($type, 'mastercard', 'visa'));
                $this->assertFalse($v->validate());
                $v->rule('creditCard', 'test', $type);
                $this->assertFalse($v->validate());
                $v->rule('creditCard', 'test', $type, array($type, 'mastercard', 'visa'));
                $this->assertFalse($v->validate());
                $v->rule('creditCard', 'test', 'invalidCardName');
                $this->assertFalse($v->validate());
                $v->rule('creditCard', 'test', 'invalidCardName', array('invalidCardName', 'mastercard', 'visa'));
                $this->assertFalse($v->validate());
                unset($v);
            }
        }
    }
}

function sampleFunctionCallback($field, $value, array $params) {
  return true;
}
