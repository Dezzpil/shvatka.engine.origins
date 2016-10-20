<?php
namespace App;

/**
 * 
 * @date 20.10.2016
 * @author Nikita Dezzpil Orlov <nikita@shvatka.ru>
 */
class Application extends \Silex\Application
{
    /**
     * http://silex.sensiolabs.org/doc/master/providers/twig.html
     */
    use \Silex\Application\TwigTrait;

    public function __construct(array $values = array()) {
        parent::__construct($values);
    }
    
    /**
     * Определить глобальную переменную с объектом
     * к которой можно обратиться в шаблоне,
     * например, 'auth' Auth
     * @param string $name
     * @param mixed $object
     */
    function setTemplateVariable($name, $object)
    {
        /* @var \Twig_Environment $twig */
        $twig = $this['twig'];
        $twig->addGlobal($name, $object);
        return $this;
    }
}
