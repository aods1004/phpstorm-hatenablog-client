<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Entries;
use App\Entity\Feed;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class FetchHatenaBlogEntriesCommand
 * @package App\Command
 */
class FetchHatenaBlogEntriesCommand extends Command
{

    /** @var Client */
    protected $client;
    /** @var string */
    protected $username;
    /** @var string */
    protected $blogId;
    /** @var SerializerInterface */
    protected $serializer;

    /**
     * FetchHatenaBlogEntriesCommand constructor.
     * @param SerializerInterface $serializer
     * @param ClientInterface|null $client
     * @param string $username
     * @param string $blogId
     */
    public function __construct(SerializerInterface $serializer, ClientInterface $client = null, string $username = '', string $blogId = '')
    {
        $this->serializer = $serializer;
        $this->client = $client;
        $this->username = $username;
        $this->blogId = $blogId;
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
        // $this->serializer = new Serializer([new AtomFeedNormalizer()], [new XmlEncoder()]);

        $entries = new Entries();
        $feed = $this->fetch("/{$this->username}/{$this->blogId}/atom/entry", $entries);
        while ($uri = $feed->getNextLinkUri()) {
            $feed = $this->fetch($uri, $entries);
        }
        foreach ($entries as $entry) {
            var_dump($entry->getTitle());
        }

    }

    /**
     * @param $uri
     * @param Entries|null $globalEntries
     * @return Feed
     */
    protected function fetch($uri, ?Entries $globalEntries = null): Feed
    {
        /** @var Feed $feed */
        $feed = $this->serializer->deserialize(
            $this->client->get($uri)->getBody()->getContents(), Feed::class, 'xml', [
            'global_entries_collection' => $globalEntries
        ]);
        return $feed;
    }
}