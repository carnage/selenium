<?php

namespace Carnage\Selenium\Collection;

use Carnage\Selenium\Exception\StepFailed;
use Carnage\Selenium\Primitive\Action;
use Carnage\Selenium\Primitive\Assert;
use Carnage\Selenium\WebDriver\RemoteWebDriver;
use Carnage\Selenium\Primitive\ExecutableInterface;

class Step
{
    private $primitives = [];
    private $failedAt = null;
    private $hasRun = false;

    private function __construct(ExecutableInterface ...$primitives)
    {
        $this->primitives = $primitives;
    }

    public static function fromPrimitives(ExecutableInterface ...$primitives)
    {
        return new static(...$primitives);
    }

    public function execute(RemoteWebDriver $webDriver)
    {
        //@TODO catch exceptions thrown by primitives and wrap with a step exception class
        $this->hasRun = true;
        foreach ($this->primitives as $index => $primitive) {
            try {
                $primitive->execute($webDriver);
            } catch (\Exception $e) {
                $this->failedAt = $index;
                throw new StepFailed('', 0 , $e);
            }
        }
    }
    
    public function __toString()
    {
        $prepend = $this->hasRun ? ': Passed' : '';
        $strings = [];

        foreach ($this->primitives as $index => $primitive) {
            $string = 'Unknown primitive';

            if ($primitive instanceof Action) {
                $string = '    -> ' . ucfirst((string) $primitive);
            } elseif ($primitive instanceof Assert) {
                $string = '    Assert that ' . (string) $primitive;
            }

            $strings[] = $string . ($index === $this->failedAt ? ': Failed' : $prepend);
        }

        return implode("\n", $strings);
    }
}