<?php

namespace Carnage\Selenium\Collection;

use Carnage\Selenium\Exception\TestSuiteFailed;
use Carnage\Selenium\WebDriver\RemoteWebDriver;

class TestSuite
{
    private $useCases;
    private $failedAt;
    private $hasRun = false;

    private function __construct(UseCase ... $useCases)
    {
        $this->useCases = $useCases;
    }

    public static function fromUseCases(UseCase ... $useCases)
    {
        return new static(... $useCases);
    }

    public function execute(RemoteWebDriver $webDriver)
    {
        $this->hasRun = true;
        foreach($this->useCases as $index => $useCase) {
            try {
                $useCase->execute($webDriver);
            } catch (\Exception $e) {
                $this->failedAt = $index;
                throw new TestSuiteFailed('', 0 , $e);
            }
        }
    }

    public function __toString()
    {
        $prepend = $this->hasRun ? ': Passed' : '';
        $strings = [];

        foreach ($this->useCases as $index => $step) {
            $string = 'Use case #' . (string) ($index + 1) . ($index === $this->failedAt ? ': Failed' : $prepend);
            $strings[] = $string . "\n" . (string) $step;
        }

        return implode("\n\n", $strings) . "\n";
    }
}