<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entry;
use App\Twig\Meta;
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
    /** @var \Twig_Environment */
    protected $twig;


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
    }

    /**
     * @param Entry $entry
     */
    public function save(Entry $entry)
    {
        $content = $this->serializer->serialize($entry, 'content_md');
        $dirname = strtr($entry->getId(), ['tag:blog.hatena.ne.jp,2013:blog-' => '', ':' => '_',]);
        $ext = $entry->getContent()->getType()->getExtension();
        $name = $entry->getContent()->getType()->getTypeName();
        $pathBase =  $this->localDir . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $dirname;
        $this->filesystem->dumpFile($pathBase . '/data/remote.json', $this->serializer->serialize($entry, 'json'));
        $this->filesystem->dumpFile( $pathBase . '/content'.$ext.'.twig', $content);

        /**
         * @type Entry $fileEntry
         */
        $fileEntry = $this->serializer->deserialize($content, Entry::class, 'content_md');

        if ($entry->getHash() != $fileEntry->getHash()) {
            // var_dump($entry->getCategories(), $fileEntry->getCategories());
        }
    }

    public function findEditedEntry()
    {
        $finder = Finder::create();
        $filesystem = new Filesystem();
        /** @var SplFileInfo $file */
        foreach ($finder->files()->in($this->localDir)->name('*.twig') as $file) {
            $templateName = $file->getBasename();
            $meta = new Meta();
            $twig = $this->buildTwig($file->getPath(), $meta);
            $remote = json_decode(file_get_contents($file->getPath().'/data/remote.json'), true);
            $xml = $globalTwig->render('entry.xml.twig', [
                'title' => $remote['title'],
                'content' => $twig->render($templateName, []),
                'category' => $remote['category'],
                'updated' => $remote['updated'],
                'draft' => $remote['control']['app:draft'],
            ]);
            var_dump($meta->getMeta());
            $filesystem->dumpFile($file->getPath()."/entry.xml", $xml);
        }
    }

}