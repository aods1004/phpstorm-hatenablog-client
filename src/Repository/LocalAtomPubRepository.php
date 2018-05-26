<?php

declare(strict_types=1);

namespace App\Repository;

use App\Encoder\EntryMarkdownEncoder;
use App\Entity\Entries;
use App\Entity\Entry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class AtomPubRepository
 * @package App\Repository
 */
class LocalAtomPubRepository implements AtomPubRepositoryInterface
{

    /** @var Filesystem */
    protected $filesystem;
    /** @var Finder */
    protected $finder;
    /** @var SerializerInterface */
    protected $serializer;
    /** @var string */
    protected $localDir;
    /** @var string */
    protected $localPublicDir;
    /** @var string */
    protected $entryIdPrefix;
    /** @var \Twig_Environment */
    protected $twig;
    /** @var string */
    protected $dataDir = '.data';
    /** @var string */
    protected $metaJsonFileName = 'meta.json';

    /**
     * LocalAtomPubRepository constructor.
     * @param string $localDir
     * @param SerializerInterface $serializer
     */
    public function __construct(
        string $localDir = '',
        SerializerInterface $serializer = null
    )
    {
        $this->finder = Finder::create();
        $this->filesystem = new Filesystem();
        $this->serializer = $serializer;
        $this->localDir = realpath($localDir);
        $this->localPublicDir = realpath($localDir) . DIRECTORY_SEPARATOR . 'public';
        $this->entryIdPrefix = 'tag:blog.hatena.ne.jp,2013:blog-aods1004-12704346814674010979-';
    }
    /**
     * @param Entry $entry
     */
    public function save(Entry $entry)
    {
        $metaJson = $this->serializer->serialize($entry, 'json');
        $this->filesystem->dumpFile($this->createMetaFilePathByEntry($entry), $metaJson);
        $this->filesystem->dumpFile($this->createMetaFilePathByEntry($entry, "." . $entry->getEdited()->format('YmdHis')), $metaJson);
        $this->filesystem->dumpFile( $this->createContentFilePathByEntry($entry),
            $this->serializer->serialize($entry, EntryMarkdownEncoder::CONTENT_MARKDOWN));
    }

    /**
     * @return Entries
     */
    public function findLocalUpdatedEntry()
    {
        $entries = new Entries();
        /** @var SplFileInfo $file */
        foreach ($this->finder->files()->in($this->localDir.'/public')->name('*.md.twig') as $file) {
            /** @type Entry $remoteEntry */
            $remoteEntry = $this->serializer->deserialize(
                file_get_contents($this->createMetaFilePathByFileInfo($file)),
                Entry::class, 'json');
            /** @type Entry $fileEntry */
            $fileEntry = $this->serializer->deserialize(
                $file->getContents(), Entry::class, EntryMarkdownEncoder::CONTENT_MARKDOWN,
                ['remote_entry' => $remoteEntry, 'edited' => date(DATE_ATOM, $file->getCTime())]);

            if ($fileEntry->getHash() !== $remoteEntry->getHash()) {
                // var_dump($fileEntry->getEdited());
                $entries->append($fileEntry);
            }
        }
        return $entries;
    }
    /**
     * @param Entry $entry
     * @param null|string $suffix
     * @return string
     */
    private function createMetaFilePathByEntry(Entry $entry, ?string $suffix = ''): string
    {
        $path = $this->createSaveDirPath($entry)
            . DIRECTORY_SEPARATOR . $this->dataDir
            . DIRECTORY_SEPARATOR . $this->metaJsonFileName.$suffix;
        return $path;
    }
    /**
     * @param Entry $entry
     * @return string
     */
    private function createContentFilePathByEntry(Entry $entry): string
    {
        return $this->createSaveDirPath($entry)
            . DIRECTORY_SEPARATOR . 'content.md.twig';
    }
    /**
     * @param SplFileInfo $info
     * @return string
     */
    private function createMetaFilePathByFileInfo(SplFileInfo $info): string
    {
        $path = $info->getPath()
            . DIRECTORY_SEPARATOR . $this->dataDir
            . DIRECTORY_SEPARATOR . $this->metaJsonFileName;
        return $path;
    }
    /**
     * @param Entry $entry
     * @return string
     */
    private function createSaveDirPath(Entry $entry)
    {
        // str_replace($this->entryIdPrefix, '', trim($entry->getId()));
        return $this->localDir
            . DIRECTORY_SEPARATOR . 'public'
            . DIRECTORY_SEPARATOR . $entry->getPublished()->format('YmdHis');
    }
}