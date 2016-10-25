<?php
namespace App\Tests;

/**
 * 
 * @date 23.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
abstract class OrganizationControllerTest extends ControllerTest
{
    /**
     *
     * @var \App\Auth
     */
    protected $_auth = null;

    function setUp()
    {
        parent::setUp();
        $this->_auth = $this->_loginUser();
        $name = $this->_auth->getAuthedMemder()['name'];
        $this->_makeAdmin($name);
        echo 'a';
        
    }
    
    function tearDown()
    {
        $name = $this->_auth->getAuthedMemder()['name'];
        $this->_unmakeAdmin($name);
        echo ';r';
        parent::tearDown();
    }
}
