<?php
class StackTest extends PHPUnit_Framework_TestCase
{
    protected $stack;
 
    protected function setUp()
    {
        $this->stack = array();
    }
 
    public function testEmpty()
    {
        $this->assertTrue(empty($this->stack));
    }
 
    public function testPush()
    {
        array_push($this->stack, 'foo1');
        $this->assertEquals('foo1', $this->stack[count($this->stack)-1]);
        $this->assertFalse(empty($this->stack));
    }
 
    public function testPop()
    {
        $this->stack[] =  'foo2';
        exit(print_r($this->stack));
        $this->assertEquals('foo2', array_pop($this->stack));
        $this->assertTrue(empty($this->stack));
    }
}
?>