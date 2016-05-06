<?php

namespace Carnage\Selenium\Primitive;

use Carnage\Selenium\WebDriver\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class Element
{
    const ID = 'id';
    const CSS_SELECTOR = 'css-selector';

    private $type;
    private $value;

    private function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public static function byId($id)
    {
        return new static(self::ID, $id);
    }

    public static function byCssSelector($selector)
    {
        return new static(self::CSS_SELECTOR, $selector);
    }

    public function find(RemoteWebDriver $webDriver)
    {
        switch ($this->type) {
            case self::ID:
                return $webDriver->findElement(WebDriverBy::id($this->value));
            case self::CSS_SELECTOR:
                return $webDriver->findElement(WebDriverBy::cssSelector($this->value));
            default:
                throw new \LogicException('Invalid element search type: ' . $this->type);
        }
    }

    public function __toString()
    {
        switch ($this->type) {
            case self::ID:
                return  'element (by id: "' . $this->value . '")';
            case self::CSS_SELECTOR:
                return  'element (by css: "' . $this->value . '")';
            default:
                return  'element (by unknown: "' . $this->value . '")';
        }
    }
}