<?php

namespace Carnage\Selenium\Primitive;

use Carnage\Selenium\WebDriver\RemoteWebDriver;

interface ExecutableInterface
{
    public function execute(RemoteWebDriver $webdriver);
}