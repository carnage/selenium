<?php

namespace Carnage\Selenium\Primitive;

use Carnage\Selenium\WebDriver\RemoteWebDriver;

class Action implements ExecutableInterface
{
    const CLICK = 'click';
    const PRESS_KEY = 'press-key';
    const TYPE = 'type';

    private $type;
    private $element;
    private $text;
    private $key;

    private function __construct($type, Element $element, $text, $key)
    {
        $this->type = $type;
        $this->element = $element;
        $this->text = $text;
        $this->key = $key;
    }

    public static function click(Element $element)
    {
        return new static(self::CLICK, $element, null, null);
    }

    public static function pressKey($key)
    {
        return new static(self::PRESS_KEY, null, null, $key);
    }

    public static function type($text, Element $intoElement)
    {
        return new static(self::TYPE, $intoElement, $text, null);
    }

    public function execute(RemoteWebDriver $webDriver)
    {
        //@TODO catch webdriver exceptions and convert into action specific exceptions
        switch ($this->type) {
            case self::TYPE:
                $element = $this->element->find($webDriver);
                $element->sendKeys($this->text);
                break;
            case self::CLICK:
                $element = $this->element->find($webDriver);
                $element->click();
                break;
            case self::PRESS_KEY:
                $webDriver->pressKey($this->key);
                break;

            default:
                throw new \LogicException('Invalid action type: '. $this->type);
        }
    }

    public function __toString()
    {
        switch ($this->type) {
            case self::TYPE:
                return 'type ' . $this->text . ' into ' . (string) $this->element;
            case self::CLICK:
                return 'click ' . (string) $this->element;
            case self::PRESS_KEY:
                return 'press ' . $this->key . ' key';
            default:
                return 'unknown action type';
        }
    }
}