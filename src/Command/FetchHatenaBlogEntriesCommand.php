<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\LocalAtomPubRepository;
use App\Repository\RemoteAtomPubRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FetchHatenaBlogEntriesCommand
 * @package App\Command
 */
class FetchHatenaBlogEntriesCommand extends Command
{
    /** @var RemoteAtomPubRepository */
    protected $remoteRepository;
    /** @var LocalAtomPubRepository */
    protected $localRepository;
    /** @var string  */
    protected $dir;

    /**
     * FetchHatenaBlogEntriesCommand constructor.
     * @param RemoteAtomPubRepository $remoteRepository
     * @param LocalAtomPubRepository $localRepository
     */
    public function __construct(RemoteAtomPubRepository $remoteRepository, LocalAtomPubRepository $localRepository)
    {
        $this->remoteRepository = $remoteRepository;
        $this->localRepository = $localRepository;
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
        foreach ($this->remoteRepository->getEntriesAll() as $entry) {
            $this->localRepository->save($entry);
        }
    }
}