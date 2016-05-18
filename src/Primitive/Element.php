<?php

namespace Carnage\Selenium\Primitive;

use Carnage\Selenium\WebDriver\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class Element
{
    const ID = 'id';
    const CSS_SELECTOR = 'css-selector';
    const LINK_TEXT = 'link-text';

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

    public static function byLinkText($text)
    {
        return new static(self::LINK_TEXT, $text);
    }

    public function find(RemoteWebDriver $webDriver)
    {
        switch ($this->type) {
            case self::ID:
                return $webDriver->findElement(WebDriverBy::id($this->value));
            case self::CSS_SELECTOR:
                return $webDriver->findElement(WebDriverBy::cssSelector($this->value));
            case self::LINK_TEXT:
                return $webDriver->findElement(WebDriverBy::linkText($this->value));
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
            case self::LINK_TEXT:
                return  'element (by link text: "' . $this->value . '")';
            default:
                return  'element (by unknown: "' . $this->value . '")';
        }
    }
}