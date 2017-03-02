<?php

namespace PhpGitHooks\Infrastructure\Common;

use PhpGitHooks\Command\OutputHandlerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Class ToolHandler.
 */
abstract class ToolHandler
{
    /** @var OutputHandlerInterface  */
    protected $outputHandler;
    /** @var  OutputInterface */
    protected $output;
    /** @var  Progress */
    protected $progress;

    /**
     * @param OutputHandlerInterface $outputHandler
     */
    public function __construct(OutputHandlerInterface $outputHandler)
    {
        $this->outputHandler = $outputHandler;
    }

    /**
     * @param OutputInterface $outputInterface
     */
    public function setOutput(OutputInterface $outputInterface)
    {
        $this->output = $outputInterface;
    }

    /**
     * @param array $files
     */
    public function setFiles(array $files)
    {
        $this->files = $files;

        // create a new progress bar
        $this->progress = new ProgressBar($this->output, count($this->files));
    }
}
