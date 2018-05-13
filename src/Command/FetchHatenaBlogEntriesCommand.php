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

    /** @var string|null */
    protected $user;
    /** @var string|null */
    protected $password;

    /**
     * FetchHatenaBlogEntriesCommand constructor.
     * @param null|string $user
     * @param null|string $password
     */
    public function __construct(?string $user = null, ?string $password = null)
    {
        $this->user = $user;
        $this->password = $password;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:fetch-entries')
            ->setDescription('fetch HatenaBlog entries');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        var_dump($this->password, $this->user);
    }
}