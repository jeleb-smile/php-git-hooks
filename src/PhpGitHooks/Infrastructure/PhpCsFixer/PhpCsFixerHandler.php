<?php

namespace PhpGitHooks\Infrastructure\PhpCsFixer;

use PhpGitHooks\Application\Message\MessageConfigData;
use PhpGitHooks\Command\BadJobLogo;
use PhpGitHooks\Infrastructure\Common\InteractiveToolInterface;
use PhpGitHooks\Infrastructure\Common\ToolHandler;
use Symfony\Component\Process\ProcessBuilder;

class PhpCsFixerHandler extends ToolHandler implements InteractiveToolInterface, PhpCsFixerHandlerInterface
{
    /** @var string */
    private $filesToAnalyze;
    /** @var array */
    private $levels = [];
    /** @var string */
    private $options = null;

    /**
     * @throws PhpCsFixerException
     */
    public function run(array $messages)
    {
        foreach ($this->levels as $level => $value) {
            if (true === $value) {
                // start and displays the progress bar
                $this->progress->start();
                // start and displays the progress bar
                $this->progress->setMessage('Checking '.strtoupper($level).' code style with PHP-CS-FIXER');
                $this->output->write($this->outputHandler->getTitle());
                $errors = array();

                foreach ($this->files as $file) {
                    $this->progress->advance();
                    $srcFile = preg_match($this->filesToAnalyze, $file);

                    if (!$srcFile) {
                        continue;
                    }

                    $processBuilder = new ProcessBuilder(
                        array(
                            'php',
                            'bin/php-cs-fixer',
                            $this->options,
                            'fix',
                            $file,
                            '--level='.$level,
                        )
                    );

                    $phpCsFixer = $processBuilder->getProcess();
                    $phpCsFixer->run();

                    if (false === $phpCsFixer->isSuccessful()) {
                        $errors[] = $phpCsFixer->getOutput();
                    }
                }

                if ($errors) {
                    $this->output->writeln(BadJobLogo::paint($messages[MessageConfigData::KEY_ERROR_MESSAGE]));
                    throw new PhpCsFixerException(implode('', $errors));
                }

                $this->progress->finish();

                $this->output->writeln($this->outputHandler->getSuccessfulStepMessage());
            }
        }
    }

    /**
     * @param string $filesToAnalyze
     */
    public function setFilesToAnalyze($filesToAnalyze)
    {
        $this->filesToAnalyze = $filesToAnalyze;
    }

    /**
     * @param array $levels
     */
    public function setLevels(array $levels)
    {
        $this->levels = $levels;
    }
    /**
     * @param array $options
     */
    public function setOptions($options = '--dry-run')
    {
        $this->options = $options;
    }
}
