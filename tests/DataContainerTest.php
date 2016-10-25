<?php
namespace App\Tests;

/**
 * 
 * @date 23.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class DataContainerTest extends \PHPUnit_Framework_TestCase
{
    use ApplicationCreator;
    
    public function testNoSuchIndex()
    {
        $container = new \App\DataContainer(['foo' => 'bar']);
        $this->assertNull($container['pew']);
    }
}
