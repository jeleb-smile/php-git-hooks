<?php

namespace PhpGitHooks\Application\CodeSniffer;

use PhpGitHooks\Application\Config\HookConfigInterface;
use PhpGitHooks\Infrastructure\CodeSniffer\CodeSnifferHandler;
use PhpGitHooks\Infrastructure\CodeSniffer\InvalidCodingStandardException;
use PhpGitHooks\Infrastructure\Common\PreCommitExecutor;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CheckCodeStyleCodeSnifferPreCommitExecutor.
 */
class CheckCodeStyleCodeSnifferPreCommitExecutor extends PreCommitExecutor
{
    /** @var CodeSnifferHandler */
    private $codeSnifferHandler;

    /**
     * @param HookConfigInterface $preCommitConfig
     * @param CodeSnifferHandler  $codeSnifferHandler
     */
    public function __construct(HookConfigInterface $preCommitConfig, CodeSnifferHandler $codeSnifferHandler)
    {
        $this->preCommitConfig = $preCommitConfig;
        $this->codeSnifferHandler = $codeSnifferHandler;
    }

    /**
     * @param OutputInterface $output
     * @param array           $files
     * @param string          $needle
     *
     * @throws InvalidCodingStandardException
     */
    public function run(OutputInterface $output, array $files, $needle)
    {
        $data = $this->preCommitConfig->extraOptions($this->commandName());

        if (true === $data['enabled']) {
            if (!is_array($data['standard'])) {
                $data['standard'] = array($data['standard'] => true);
            }
            foreach ($data['standard'] as $standard => $isActivated) {
                if (true === $isActivated) {
                    $this->process($output, $files, $needle, $standard);
                }
            }
        }
    }

    /**
     * [process description].
     *
     * @param OutputInterface $output   [description]
     * @param array           $files    [description]
     * @param [type]          $needle   [description]
     * @param [type]          $standard [description]
     *
     * @throws InvalidCodingStandardException
     */
    protected function process(OutputInterface $output, array $files, $needle, $standard)
    {
        $this->codeSnifferHandler->setOutput($output);
        $this->codeSnifferHandler->setFiles($files);
        $this->codeSnifferHandler->setNeddle($needle);
        $this->codeSnifferHandler->setStandard($standard);
        $this->codeSnifferHandler->run($this->getMessages());
    }

    /**
     * @return string
     */
    protected function commandName()
    {
        return 'phpcs';
    }
}
