<?php

/*
 * DesignPatternPHP
 */

namespace DesignPatterns\Test\DataMapper;

use DesignPatterns\DataMapper\UserMapper;
use DesignPatterns\DataMapper\User;

/**
 * UserMapperTest tests the datamappe pattern
 */
class UserMapperTest extends \PHPUnit_Framework_TestCase
{

    protected $mapper;
    protected $dbal;

    protected function setUp()
    {
        $this->dbal = $this->getMockBuilder('DesignPatterns\DataMapper\DBAL')
                ->disableAutoload()
                ->setMethods(array('insert', 'update', 'find', 'findAll'))
                ->getMock();

        $this->mapper = new UserMapper($this->dbal);
    }

    public function getNewUser()
    {
        return array(array(new User(null, 'Odysseus', 'Odysseus@ithaca.gr')));
    }

    public function getExistingUser()
    {
        return array(array(new User(1, 'Odysseus', 'Odysseus@ithaca.gr')));
    }

    /**
     * @dataProvider getNewUser
     */
    public function testPersistNew(User $user)
    {
        $this->dbal->expects($this->once())
                ->method('insert');
        $this->mapper->save($user);
    }

    /**
     * @dataProvider getExistingUser
     */
    public function testPersistExisting(User $user)
    {
        $this->dbal->expects($this->once())
                ->method('update');
        $this->mapper->save($user);
    }

    /**
     * @dataProvider getExistingUser
     */
    public function testRestoreOne(User $stored)
    {
        $this->dbal->expects($this->once())
                ->method('find')
                ->with(1)
                ->will($this->returnValue(new \ArrayIterator(array($stored))));

        $user = $this->mapper->findById(1);
        echo "Hello " . $user->getUsername() . ". Your email is " . $user->getEmail();
    }

}