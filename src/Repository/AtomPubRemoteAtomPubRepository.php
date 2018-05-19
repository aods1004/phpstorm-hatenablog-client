<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entries;
use App\Entity\Feed;
use App\Entity\Link;
use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\uri_for;
use Psr\Http\Message\UriInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AtomPubRepository
 * @package App\Repository
 */
class AtomPubRemoteAtomPubRepository implements AtomPubRepositoryInterface
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

    public function fetchAll()
    {
        $feeds = [];
        $feed = $this->fetch($this->firstFeedUri);
        while ($links = $feed->getLinks()->getLinksByRel(Link::RELATION_NEXT)) {
            foreach ($links as $link) {
                $feed = $this->fetch($link->getHref());
                foreach ($feed->getEntries() as $entry) {
                    $this->entries->append($entry);
                }
            }
        }
        return $feeds;
    }

    /**
     * @param UriInterface $uri
     * @param Entries|null $globalEntries
     * @return Feed
     */
    public function fetch(UriInterface $uri, ?Entries $globalEntries = null): Feed
    {
        /** @var Feed $feed */
        $feed = $this->serializer->deserialize(
            $this->client->get($uri)->getBody()->getContents(), Feed::class, 'xml');
        return $feed;
    }

    /**
     * @return Entries
     */
    public function getEntries(): Entries
    {
        $this->fetchAll();
        return $this->entries;
    }
}