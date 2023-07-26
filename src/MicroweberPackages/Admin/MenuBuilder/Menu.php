<?php

namespace MicroweberPackages\Admin\MenuBuilder;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class Menu
{

    public ItemInterface $menuItems;
    public FactoryInterface $menuFactory;

    public $rendererOptions = [];

    public function __construct($name = 'default', array $options = [])
    {

        $this->menuFactory = new \Knp\Menu\MenuFactory();

        $this->menuItems = $this->menuFactory->createItem($name, $options);
    }

    public function addChild($title, $options = [])
    {
        $this->menuItems->addChild($title, $options);
        return $this;
    }

    public function getChild($title)
    {
        return $this->menuItems->getChild($title);
    }

    public function getItems()
    {
        return $this->menuItems->getChildren();
    }
    public function setRendererOptions($options)
    {
        $this->rendererOptions = $options;
        return $this;
    }

    public function render()
    {
        $rendererOptions = $this->rendererOptions;

        $renderer = new \Knp\Menu\Renderer\ListRenderer(new \Knp\Menu\Matcher\Matcher(), $rendererOptions);
        return $renderer->render($this->menuItems);
    }

}
