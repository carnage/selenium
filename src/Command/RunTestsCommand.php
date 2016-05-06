<?php

namespace Carnage\Selenium\Command;

use Carnage\Selenium\Collection\TestSuite;
use Carnage\Selenium\ValueObject\Url;
use Carnage\Selenium\WebDriver\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver as FacebookRemoteWebDriver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunTestsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('carnage-selenium:run-tests')
            ->setDescription('Runs Selenium tests')
            /*->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )*/
            ->addOption(
                'selenium-host',
                null,
                InputOption::VALUE_REQUIRED,
                'The url of the selenium hub to connect to',
                'http://localhost:4444/wd/hub'
            )
            ->addOption(
                'test-suite',
                null,
                InputOption::VALUE_REQUIRED,
                'The test suite to run',
                './selenium-suite.php'
            )
            ->addOption(
                'base-url',
                null,
                InputOption::VALUE_REQUIRED,
                'The base url to use for tests',
                'http://localhost'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var TestSuite $testSuite */
        $testSuite = require $input->getOption('test-suite');

        $host = $input->getOption('selenium-host');

        $facebookDriver = FacebookRemoteWebDriver::create($host, DesiredCapabilities::chrome());

        $remoteDriver = new RemoteWebDriver($facebookDriver, Url::fromString($input->getOption('base-url')));
        try {
            $testSuite->execute($remoteDriver);
        } catch (\Exception $e) {
            do {
                $message = $e->getMessage();
            } while ($e = $e->getPrevious());
        }

        $report = $this->formatReport($testSuite);

        $output->writeln((string) $report);
        if (isset($message)) {
            $output->writeln("<error>" . $message . "<error>");
        }
    }

    /**
     * @param $testSuite
     * @return string
     */
    private function formatReport($testSuite)
    {
        $report = (string)$testSuite;
        $report = str_replace(
            ['Passed', 'Failed'],
            ['<fg=green>Passed</>', '<fg=red>Failed</>'],
            $report
        );

        $report = preg_replace('#"([^"]+)"#', '"<fg=blue>\1</>"', $report);
        $report = preg_replace('#(Use case .+:)#', '<question>\1</question>', $report);
        return $report;
    }
}