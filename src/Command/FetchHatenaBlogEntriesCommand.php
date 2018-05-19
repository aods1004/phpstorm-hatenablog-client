<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\AtomPubRemoteAtomPubRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FetchHatenaBlogEntriesCommand
 * @package App\Command
 */
class FetchHatenaBlogEntriesCommand extends Command
{
    /** @var AtomPubRemoteAtomPubRepository */
    protected $remoteRepository;

    /**
     * FetchHatenaBlogEntriesCommand constructor.
     * @param AtomPubRemoteAtomPubRepository $remoteRepository
     */
    public function __construct(AtomPubRemoteAtomPubRepository $remoteRepository)
    {
        $this->remoteRepository = $remoteRepository;
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
        foreach ($this->remoteRepository->getEntries() as $entry) {
            var_dump($entry->getUpdated()->format(DATE_ATOM), $entry->getTitle());

        }

    }

}