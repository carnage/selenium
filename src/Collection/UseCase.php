<?php

namespace Carnage\Selenium\Collection;

use Carnage\Selenium\Exception\UseCaseFailed;
use Carnage\Selenium\ValueObject\Uri;
use Carnage\Selenium\WebDriver\RemoteWebDriver;

class UseCase
{
    private $steps;
    private $uri;
    private $failedAt;
    private $hasRun = false;

    private function __construct(Uri $uri, Step ...$steps)
    {
        $this->uri = $uri;
        $this->steps = $steps;
    }

    public static function fromSteps(Uri $url, Step ...$steps)
    {
        return new static($url, ...$steps);
    }

    public function execute(RemoteWebDriver $webDriver)
    {
        $webDriver->getUri($this->uri);
        $this->hasRun = true;

        foreach ($this->steps as $index => $step) {
            try {
                $step->execute($webDriver);
            } catch (\Exception $e) {
                $this->failedAt = $index;
                throw new UseCaseFailed('', 0 , $e);
            }
        }
    }

    public function __toString()
    {
        $prepend = $this->hasRun ? ': Passed' : '';
        $strings = ['  Starting from uri: "' . (string) $this->uri . '"'];

        foreach ($this->steps as $index => $step) {
            $string = '  Step #' . (string) ($index + 1) . ($index === $this->failedAt ? ': Failed' : $prepend);
            $strings[] = $string . "\n" . (string) $step;
        }

        return implode("\n", $strings);
    }
}