<?php


namespace VisitMarche\Theme\Lib\Elasticsearch\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use VisitMarche\Theme\Lib\Elasticsearch\ElasticIndexer;

class ElasticIndexerCommand extends Command
{
    protected static $defaultName = 'elastic:indexer';

    /**
     * @var SymfonyStyle
     */
    private $io;

    protected function configure()
    {
        $this
            ->setDescription('Mise à jour des données [all, posts, categories, offres]')
            ->addArgument('action', InputArgument::REQUIRED, 'all, posts, categories, bottin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $action = $input->getArgument('action');
        $this->io = new SymfonyStyle($input, $output);
        $elastic = new ElasticIndexer($this->io);

        switch ($action) {
            case 'posts':
                $this->io->section("POSTS");
                $elastic->indexAllPosts();
                break;
            case 'categories':
                $this->io->section("CATEGORIES");
                $elastic->indexAllCategories();
                break;
            case 'offres':
                $this->io->section("OFFRES");
                $elastic->indexAllOffres();
                break;
            case 'all':
                $this->io->section("POSTS");
                $elastic->indexAllPosts();
                $this->io->section("CATEGORIES");
                $elastic->indexAllCategories();
                $this->io->section("OFFRES");
                $elastic->indexAllOffres();
                break;
        }

        return Command::SUCCESS;
    }
}
