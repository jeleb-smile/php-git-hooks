<?php

namespace PhpGitHooks\Application\PhpUnit;

use PhpGitHooks\Application\Message\MessageConfigData;
use PhpGitHooks\Command\BadJobLogo;
use PhpGitHooks\Command\OutputHandlerInterface;
use PhpGitHooks\Infrastructure\Common\ProcessBuilderInterface;
use PhpGitHooks\Infrastructure\Common\ToolHandler;
use PhpGitHooks\Infrastructure\PhpUnit\PhpUnitProcessBuilder;

/**
 * Class PhpUnitHandler.
 */
class PhpUnitHandler extends ToolHandler
{
    /** @var PhpUnitProcessBuilder  */
    private $phpUnitProcessBuilder;

    /**
     * @param OutputHandlerInterface  $outputHandler
     * @param ProcessBuilderInterface $processBuilderInterface
     */
    public function __construct(OutputHandlerInterface $outputHandler, ProcessBuilderInterface $processBuilderInterface)
    {
        parent::__construct($outputHandler);

        $this->phpUnitProcessBuilder = $processBuilderInterface;
    }

    /**
     * @param array $messages
     *
     * @throws UnitTestsException
     */
    public function run(array $messages)
    {
        $this->setTitle();

        $processBuilder = $this->phpUnitProcessBuilder->getProcessBuilder();
        $processBuilder->setTimeout(3600);
        $phpunit = $processBuilder->getProcess();
        $this->phpUnitProcessBuilder
            ->executeProcess($phpunit, $this->output);

        if (!$phpunit->isSuccessful()) {
            $this->output->writeln(BadJobLogo::paint($messages[MessageConfigData::KEY_ERROR_MESSAGE]));
            throw new UnitTestsException();
        }
    }

    private function setTitle()
    {
        $this->outputHandler->setTitle('Running unit tests');
        $this->output->write($this->outputHandler->getTitle());
        $this->output->writeln($this->outputHandler->getSuccessfulStepMessage('Executing'));
    }
}
