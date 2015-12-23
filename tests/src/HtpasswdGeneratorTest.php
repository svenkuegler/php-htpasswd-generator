<?php

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-12-22 at 15:26:46.
 */
class HtpasswdGeneratorTest extends PHPUnit_Framework_TestCase {

    /**
     * @var HtpasswdGenerator
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new HtpasswdGenerator;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers  HtpasswdGenerator::add
     * @covers  HtpasswdGenerator::saveFile
     * @covers  HtpasswdGenerator::loadFile
     * @group   publicMethods
     */
    public function testAddNewUser() {
        $this->object->add("unittest", "123456");
        $this->assertEquals(true, $this->object->isValid("unittest", "123456"));
    }

    /**
     * @covers  HtpasswdGenerator::delete
     * @covers  HtpasswdGenerator::saveFile
     * @covers  HtpasswdGenerator::loadFile
     * @group   publicMethods
     */
    public function testDeleteExistingUser() {
        $this->object->delete("unittest");
        $this->assertEquals(false, $this->object->isValid("unittest", "123456"));
        $this->assertEquals(false, array_key_exists("unittest", $this->object->getUsers()));
    }

    /**
     * @covers HtpasswdGenerator::isValid
     * @group  publicMethods
     */
    public function testIsValid() {
        $this->object->setUser("testuser1", '$apr1$70crho1l$tuUp8v81nAPPbIMkAOehn1');
        $this->assertEquals(true, $this->object->isValid("testuser1", "123456"));
    }

    /**
     * @covers  HtpasswdGenerator::getUsers
     * @covers  HtpasswdGenerator::clearUsers
     * @covers  HtpasswdGenerator::setUser
     * @group   privateMethods
     */
    public function testHelper() {
        $this->assertEquals(true, is_array($this->object->getUsers()));

        $this->object->clearUsers();
        $this->assertEquals(0, count($this->object->getUsers()));

        $this->object->setUser("testuser1", '$apr1$70crho1l$tuUp8v81nAPPbIMkAOehn1');
        $this->assertEquals(1, count($this->object->getUsers()));
    }
    
    /**
     * @covers  HtpasswdGenerator::cleanUp
     * @group   privateMethods
     */
    public function testCleanupString() {
        $this->assertEquals("xxx", $this->invokeMethod($this->object, "cleanUp", array(" xxx ")));
    }
    
    /**
     * @covers HtpasswdGenerator::addMessage
     * @group publicMethods
     * @group privateMethods
     */
    public function testMessaging() {
        $this->invokeMethod($this->object, "addMessage", array("phpUnit Message 1", HtpasswdGenerator::MESSAGE_ERROR));
        $this->invokeMethod($this->object, "addMessage", array("phpUnit Message 2", HtpasswdGenerator::MESSAGE_NOTICE));
        $this->invokeMethod($this->object, "addMessage", array("phpUnit Message 3", HtpasswdGenerator::MESSAGE_SUCCESS));
    }
    
    /**
     * @covers HtpasswdGenerator::cryptApr1Md5
     * @group privateMethods
     */
    public function testCrypt() {
        $this->invokeMethod($this->object, "cryptApr1Md5", array("123456"));
    }
    
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
       $reflection = new \ReflectionClass(get_class($object));
       $method = $reflection->getMethod($methodName);
       $method->setAccessible(true);

       return $method->invokeArgs($object, $parameters);
    }

}
