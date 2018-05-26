<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Link;
use App\Repository\LocalAtomPubRepository;
use App\Repository\RemoteAtomPubRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FetchHatenaBlogEntriesCommand
 * @package App\Command
 */
class UploadHatenaBlogEntriesCommand extends Command
{
    /** @var RemoteAtomPubRepository */
    protected $remoteRepository;
    /** @var LocalAtomPubRepository */
    protected $localRepository;

    /** @var \Twig_Extension_Core */
    protected $twig;
    /** @var string  */
    protected $dir;

    /**
     * UploadHatenaBlogEntriesCommand constructor.
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
            ->setName('app:update-entries')
            ->setDescription('fetch HatenaBlog entries');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->localRepository->findLocalUpdatedEntry() as $localEntry) {
            $currentRemoteEntry = $this->remoteRepository->fetchRemoteEntry($localEntry);
            if ($currentRemoteEntry->getEdited() > $localEntry->getEdited()) {
                echo "SKIP: {$currentRemoteEntry->getTitle()} "
                    . "REMOTE EDITED : {$currentRemoteEntry->getEdited()->format(DATE_ATOM)}"
                    . "> LOCAL EDITED : {$localEntry->getEdited()->format(DATE_ATOM)}" . PHP_EOL;
                continue;
            }
            $resultEntry = $this->remoteRepository->save($localEntry);
            foreach ($resultEntry->getLinks()->getLinksByRel(Link::ALTERNATE) as $link) {
                echo "UPDATE: {$resultEntry->getTitle()} " . strval($link->getHref()) . PHP_EOL;
            }
            $this->localRepository->save($resultEntry);
        }
    }
}