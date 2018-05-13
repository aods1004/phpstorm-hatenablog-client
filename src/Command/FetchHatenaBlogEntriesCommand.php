<?php

namespace App\Command;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use EightPoints\Bundle\GuzzleBundle\EightPointsGuzzleBundle;

/**
 * Class FetchHatenaBlogEntriesCommand
 * @package App\Command
 */
class FetchHatenaBlogEntriesCommand extends Command
{

    /** @var Client */
    protected $client;
    protected $username;
    protected $blogId;

    /**
     * FetchHatenaBlogEntriesCommand constructor.
     * @param Client|null $client
     * @param string $username
     * @param string $blogId
     */
    public function __construct(Client $client = null, string $username = '', string $blogId = '')
    {
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
        $resupose = $this->client->get("/{$this->username}/{$this->blogId}/atom/entry");
        var_dump($resupose->getBody()->getContents());
    }
}