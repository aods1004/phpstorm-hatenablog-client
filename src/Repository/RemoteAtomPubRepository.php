<?php

declare(strict_types=1);

namespace App\Repository;

use App\Encoder\AtomPubEntryXmlEncoder;
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
    public function getEntriesAll(): Entries
    {
        $this->loadAll();
        return $this->entries;
    }

    /**
     * @return Entries
     */
    public function loadAll(): Entries
    {
        $feed = $this->fetchFeed($this->firstFeedUri);
        while ($nextLink = $feed->getNextLink()) {
            $feed = $this->fetchFeed($nextLink->getHref());
        }

        return $this->entries;
    }

    /**
     * @param UriInterface $uri
     * @return Feed
     */
    public function fetchFeed(UriInterface $uri): Feed
    {
        /** @var Feed $feed */
        $feed = $this->serializer->deserialize(
            $this->client->get($uri)->getBody()->getContents(), Feed::class, 'xml',
            ['global_entries' => $this->entries]);
        return $feed;
    }



    /**
     * @param Entry $entry
     * @return Entry|null
     */
    public function fetchRemoteEntry(Entry $entry): Entry
    {
        /** @var Entry $entry */
        $entry = $this->serializer->deserialize(
            $this->client->get($entry->getEditLink()->getHref())->getBody()->getContents(), Entry::class, 'xml');
        return $entry;
    }

    /**
     * @param Entry $entry
     * @return Entry
     */
    public function save(Entry $entry)
    {
        $xml = $this->serializer->serialize($entry, AtomPubEntryXmlEncoder::ENTRY_XML);
        /** @var Entry $result */
        $result = $this->serializer->deserialize(
            $this->client->put($entry->getEditLink()->getHref(), ['body' => $xml])->getBody()->getContents(),
            Entry::class, 'xml');
        return $result;
    }
}