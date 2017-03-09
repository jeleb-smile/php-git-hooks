<?php

namespace PhpGitHooks\Infrastructure\JsonLint;

use PhpGitHooks\Application\Message\MessageConfigData;
use PhpGitHooks\Command\BadJobLogo;
use PhpGitHooks\Infrastructure\Common\RecursiveToolInterface;
use PhpGitHooks\Infrastructure\Common\ToolHandler;
use Symfony\Component\Process\ProcessBuilder;

final class JsonLintHandler extends ToolHandler implements RecursiveToolInterface
{
    /** @var  string */
    private $needle;

    /**
     * @param array $messages
     *
     * @throws JsonLintViolationsException
     */
    public function run(array $messages)
    {
        // start and displays the progress bar
        $this->progress->start();
        $this->progress->setMessage(sprintf('Checking json code with %s', strtoupper('jsonlint')));
        $this->output->write($this->outputHandler->getTitle());

        $errors = [];

        foreach ($this->files as $file) {
            $this->progress->advance();

            if (!preg_match($this->needle, $file)) {
                continue;
            }

            $processBuilder = new ProcessBuilder(
                array(
                    'php',
                    'bin/jsonlint',
                    $file,
                )
            );
            $process = $processBuilder->getProcess();
            $process->run();

            if (false === $process->isSuccessful()) {
                $errors[] = $process->getOutput();
            }
        }

        $errors = array_filter($errors, function ($var) {
            return !is_null($var);
        });

        if ($errors) {
            $this->output->writeln(BadJobLogo::paint($messages[MessageConfigData::KEY_ERROR_MESSAGE]));
            throw new JsonLintViolationsException(implode('', $errors));
        }
        $this->progress->finish();

        $this->output->writeln($this->outputHandler->getSuccessfulStepMessage());
    }

    /**
     * @param string $needle
     */
    public function setNeedle($needle)
    {
        $this->needle = $needle;
    }
}
