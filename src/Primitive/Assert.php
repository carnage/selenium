<?php

namespace Carnage\Selenium\Primitive;

use Carnage\Selenium\ValueObject\Uri;
use Carnage\Selenium\ValueObject\Url;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Carnage\Selenium\WebDriver\RemoteWebDriver;
use Carnage\Selenium\Exception\AssertionFailed;

class Assert implements ExecutableInterface
{
    const ELEMENT_EXISTS = 'element-exists';
    const ELEMENT_CONTAINS = 'element-contains';
    const PAGE_URL = 'page-url';
    const PAGE_URI = 'page-uri';
    const PAGE_TITLE = 'page-title';

    private $type;
    private $element;
    private $value;

    private function __construct($type, Element $element = null, $value = null)
    {
        $this->type = $type;
        $this->element = $element;
        $this->value = $value;
    }

    public static function elementExists(Element $element)
    {
        return new static(self::ELEMENT_EXISTS, $element, null);
    }

    public static function elementContains(Element $element, $text)
    {
        return new static(self::ELEMENT_CONTAINS, $element, $text);
    }

    public static function urlEquals(Url $url)
    {
        return new static(self::PAGE_URL, null, $url);
    }

    public static function uriEquals(Uri $url)
    {
        return new static(self::PAGE_URI, null, $url);
    }

    public static function pageTitleEquals($title)
    {
        return new static(self::PAGE_TITLE, null, $title);
    }

    private function testAssertion(RemoteWebDriver $webDriver)
    {
        switch ($this->type) {
            case self::ELEMENT_CONTAINS:
                try {
                    $element = $this->element->find($webDriver);
                } catch (NoSuchElementException $e) {
                    return false;
                }

                return ($element->getText() === $this->value);
            case self::ELEMENT_EXISTS:
                try {
                    $this->element->find($webDriver);
                } catch (NoSuchElementException $e) {
                    return false;
                }

                return true;
            case self::PAGE_TITLE:
                return $webDriver->getTitle() === $this->value;
            case self::PAGE_URL:
                return $webDriver->getCurrentUrl()->equals($this->value);
            case self::PAGE_URI:
                return $webDriver->getCurrentUri()->equals($this->value);
            default:
                throw new \LogicException('Invalid assertion type: '. $this->type);
        }
    }

    public function execute(RemoteWebDriver $webDriver)
    {
        if (!$this->testAssertion($webDriver)) {
            //@TODO make this return better information
            throw new AssertionFailed('Failed asserting that ' . (string) $this);
        }
    }

    public function __toString()
    {
        switch ($this->type) {
            case self::ELEMENT_CONTAINS:
                return (string) $this->element  . ' contains "' . $this->value .'"';
            case self::ELEMENT_EXISTS:
                return (string) $this->element . ' exists';
            case self::PAGE_TITLE:
                return 'page title is "' . $this->value . '"';
            case self::PAGE_URL:
                return 'page url is "' . (string) $this->value . '"';
            case self::PAGE_URI:
                return 'page uri is "' . (string) $this->value . '"';
            default:
                return 'unknown assertion';
        }
    }
}