<?php

namespace YiiContribTest\Validators;

use PHPUnit\Framework\TestCase;
use YiiContrib\Sms\Validators\PhoneValidator;

class PhoneValidatorTest extends TestCase
{
    /**
     * @var PhoneValidator
     */
    protected $validator;
    
    protected function setUp()
    {
        $this->validator = new PhoneValidator();
    }
    
    public function testValidator()
    {
        $valid = $this->validator->validate('15210345043', $error);
        
        $this->assertTrue($valid);
        $this->assertNull($error);
    }
    
    public function testInvalid()
    {
        $valid = $this->validator->validate('121231312', $error);
    
        $this->assertFalse($valid);
    }
}
