<?php

namespace ride\library\dependency;

use ride\library\reflection\ObjectFactory;

use PHPUnit\Framework\TestCase;

class DependencyTest extends TestCase {

    public function testSetClassName() {
        $className = 'className';

        $dependency = new Dependency($className);

        $this->assertEquals($className, $dependency->getClassName());
        $this->assertNull($dependency->getId());
        $this->assertNull($dependency->getConstructCall());
        $this->assertNull($dependency->getConstructorArguments());
        $this->assertNull($dependency->getCalls());
    }

    public function testRemoveInterfaceShouldReturnFalse() {
        $constructCall = new DependencyConstructCall('interface', 'method');

        $dependency = new Dependency($constructCall);

        $this->assertFalse($dependency->removeInterface('method'));
    }

    public function testAddTag() {
        $constructCall = new DependencyConstructCall('interface', 'method');

        $dependency = new Dependency($constructCall);

        $dependency->addTag('tag');

        $this->assertContains('tag', $dependency->getTags());
    }

    public function testRemoveTag() {
        $constructCall = new DependencyConstructCall('interface', 'method');

        $dependency = new Dependency($constructCall);

        $dependency->addTag('tag');

        $dependency->removeTag('tag');

        $this->assertNotContains('tag', $dependency->getTags());
    }

    public function testRemoveTagShouldReturnFalse() {
        $constructCall = new DependencyConstructCall('interface', 'method');

        $dependency = new Dependency($constructCall);

        $this->assertFalse($dependency->removeTag('tag'));
    }

    public function testHasTag() {
        $constructCall = new DependencyConstructCall('interface', 'method');

        $dependency = new Dependency($constructCall);

        $this->assertFalse($dependency->hasTag('tag'));
    }

    public function testSetConstructorCall() {
        $constructCall = new DependencyConstructCall('interface', 'method');

        $dependency = new Dependency($constructCall);

        $this->assertEquals($constructCall, $dependency->getConstructCall());
        $this->assertNull($dependency->getId());
        $this->assertNull($dependency->getClassName());
        $this->assertNull($dependency->getConstructorArguments());
        $this->assertNull($dependency->getCalls());
    }

    /**
     * @dataProvider providerSetClassNameThrowsExceptionWhenInvalidValuePassed
     * @expectedException ride\library\dependency\exception\DependencyException
     */
    public function testSetClassNameThrowsExceptionWhenInvalidValuePassed($value) {
        new Dependency($value);
    }

    public function providerSetClassNameThrowsExceptionWhenInvalidValuePassed() {
        return array(
            array(''),
            array(null),
            array(array()),
            array($this),
        );
    }

    public function testSetId() {
        $id = 'id';

        $dependency = new Dependency('className', $id);

        $this->assertEquals($id, $dependency->getId());
    }

    /**
     * @dataProvider providerSetIdThrowsExceptionWhenInvalidValuePassed
     * @expectedException ride\library\dependency\exception\DependencyException
     */
    public function testSetIdThrowsExceptionWhenInvalidValuePassed($value) {
        $dependency = new Dependency('className');
        $dependency->setId($value);
    }

    public function providerSetIdThrowsExceptionWhenInvalidValuePassed() {
        return array(
            array(''),
            array(array()),
            array($this),
        );
    }

    public function testAddCall() {
        $dependency = new Dependency('className');
        $call = new DependencyCall('methodName');

        $dependency->addCall($call);
        $expected = array('c0' => $call);

        $this->assertEquals($expected, $dependency->getCalls());

        $id = 'call';

        $call = clone $call;
        $call->setId($id);

        $dependency->addCall($call);
        $expected[$id] = $call;

        $this->assertEquals($expected, $dependency->getCalls());
    }

    public function testAddCallWithConstructorCallWillNotAddCallButSetConstructorArguments() {
        $dependency = new Dependency('className');
        $call = new DependencyCall('__construct');

        $dependency->addCall($call);

        $this->assertNull($dependency->getCalls());
        $this->assertNull($dependency->getConstructorArguments());

        $argument = new DependencyCallArgument('name', 'type');

        $call->addArgument($argument);

        $dependency->addCall($call);
        $expected = array('name' => $argument);

        $this->assertNull($dependency->getCalls());
        $this->assertEquals($expected, $dependency->getConstructorArguments());
    }

    public function testClearCalls() {
        $dependency = new Dependency('className');
        $call = new DependencyCall('setTest');

        $dependency->addCall($call);

        $this->assertNotNull($dependency->getCalls());

        $dependency->clearCalls();

        $this->assertNull($dependency->getCalls());
    }

    public function testSetInterfaces() {
        $dependency = new Dependency('className');

        $this->assertEmpty($dependency->getInterfaces());

        $interfaces = array('interface');

        $dependency->setInterfaces($interfaces);

        $this->assertEquals($interfaces, $dependency->getInterfaces());
    }

}
