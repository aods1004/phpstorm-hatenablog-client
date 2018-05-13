<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FetchHatenaBlogEntriesCommand
 * @package App\Command
 */
class FetchHatenaBlogEntriesCommand extends Command
{
    protected static $defaultName = 'app:fetch-hatena-blog-entries';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('fetch HatenaBlog entries');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo 'do something.' . PHP_EOL;
    }
}