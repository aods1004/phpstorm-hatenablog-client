<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entries;
use App\Entity\Entry;
use App\Entity\Feed;
use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\uri_for;
use Psr\Http\Message\UriInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AtomPubRepository
 * @package App\Repository
 */
class RemoteAtomPubRepository implements AtomPubRepositoryInterface
{
    /** @var UriInterface */
    protected $firstFeedUri;
    /** @var Client */
    protected $client;
    /** @var SerializerInterface */
    protected $serializer;
    /** @var Entries */
    protected $entries;

    /**
     * AtomPubRemoteAtomPubRepository constructor.
     * @param string $firstFeedUri
     * @param Client $client
     * @param SerializerInterface $serializer
     */
    public function __construct(
        string $firstFeedUri = '',
        Client $client = null,
        SerializerInterface $serializer = null
    )
    {
        $this->firstFeedUri = uri_for($firstFeedUri);
        $this->client = $client;
        $this->serializer = $serializer;
        $this->entries = new Entries();
    }

    /**
     * @return Entries
     */
    public function loadAll(): Entries
    {
        $feed = $this->fetchFeed($this->firstFeedUri);
        return $this->entries;
        while($nextLink = $feed->getNextLink()) {
            $feed = $this->fetchFeed($nextLink->getHref());
        }

        return $this->entries;
    }

    public function updateEntry(Entry $entry)
    {

    }

    /**
     * @param UriInterface $uri
     * @return Feed
     */
    public function fetchFeed(UriInterface $uri): Feed
    {
        /** @var Feed $feed */
        $feed = $this->serializer->deserialize(
            $this->client->get($uri)->getBody()->getContents(), Feed::class, 'xml');
        foreach ($feed->getEntries() as $entry) {
            $this->entries->append($entry);
        }
        return $feed;
    }

    /**
     * @return Entries
     */
    public function getEntries(): Entries
    {
        $this->loadAll();
        return $this->entries;
    }
}