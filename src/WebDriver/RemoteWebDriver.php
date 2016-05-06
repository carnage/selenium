<?php
namespace Carnage\Selenium\WebDriver;

use Carnage\Selenium\ValueObject\Uri;
use Carnage\Selenium\ValueObject\Url;
use Facebook\WebDriver\Remote\RemoteWebDriver as FacebookRemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class RemoteWebDriver
{
    /**
     * @var FacebookRemoteWebDriver
     */
    private $webDriver;

    /**
     * @var Url
     */
    private $baseUrl;

    public function __construct(FacebookRemoteWebDriver $webDriver, Url $baseUrl = null)
    {
        $this->webDriver = $webDriver;
        $this->baseUrl = $baseUrl;
    }

    public function pressKey($key)
    {
        return $this->webDriver->getKeyboard()->pressKey($key);
    }
    
    public function findElement(WebDriverBy $by)
    {
        return $this->webDriver->findElement($by);
    }

    public function getUrl(Url $url)
    {
        return $this->webDriver->get((string) $url);
    }

    public function getUri(Uri $uri)
    {
        if ($this->baseUrl === null) {
            throw new \RuntimeException('Cannot get uri: no base Url set');
        }

        return $this->getUrl($this->baseUrl->withUri($uri));
    }

    public function getTitle()
    {
        return $this->webDriver->getTitle();
    }

    /**
     * @return Url
     */
    public function getCurrentUrl()
    {
        return Url::fromString($this->webDriver->getCurrentURL());
    }

    /**
     * @return Uri
     */
    public function getCurrentUri()
    {
        return Uri::fromString($this->webDriver->getCurrentURL());
    }
}