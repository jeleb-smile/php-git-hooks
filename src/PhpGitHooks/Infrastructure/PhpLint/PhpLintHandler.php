<?php

namespace PhpGitHooks\Infrastructure\PhpLint;

use PhpGitHooks\Application\Message\MessageConfigData;
use PhpGitHooks\Command\BadJobLogo;
use PhpGitHooks\Infrastructure\Common\FilesToolHandlerInterface;
use PhpGitHooks\Infrastructure\Common\ToolHandler;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class PhpLintHandler.
 */
class PhpLintHandler extends ToolHandler implements FilesToolHandlerInterface
{
    const NEEDLE = '/(\.php)|(\.inc)$/';

    /** @var string */
    private $options = null;

    /**
     * @param array $messages
     *
     * @return mixed|void
     *
     * @throws PhpLintException
     */
    public function run(array $messages)
    {
        // start and displays the progress bar
        $this->progress->start();
        $this->progress->setMessage('Running PHPLint');
        $this->output->write($this->outputHandler->getTitle());

        foreach ($this->files as $file) {
            $this->progress->advance();
            if (!preg_match(self::NEEDLE, $file)) {
                continue;
            }

            $processBuilder = new ProcessBuilder(
                array(
                    'php',
                    '-l',
                    $this->options,
                    $file,
                )
            );
            /** @var Process $process */
            $process = $processBuilder->getProcess();
            $process->run();
        }

        /* @var Process $process */
        if (false === $process->isSuccessful()) {
            $this->outputHandler->setError($process->getErrorOutput());
            $this->output->writeln($this->outputHandler->getError());
            $this->output->writeln(BadJobLogo::paint($messages[MessageConfigData::KEY_ERROR_MESSAGE]));

            throw new PhpLintException();
        }

        $this->progress->finish();

        $this->output->writeln($this->outputHandler->getSuccessfulStepMessage());
    }
    /**
     * @param array $options
     */
    public function setOptions($options = '')
    {
        $this->options = $options;
    }
}
