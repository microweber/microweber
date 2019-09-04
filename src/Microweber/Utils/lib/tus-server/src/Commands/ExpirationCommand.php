<?php

namespace TusPhp\Commands;

use TusPhp\Config;
use TusPhp\Cache\CacheFactory;
use TusPhp\Tus\Server as TusServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
class ExpirationCommand extends Command
{
    /** @var TusServer */
    protected $server;
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('tus:expired')->setDescription('Remove expired uploads.')->setHelp('Deletes all expired uploads to free server resources. Values can be redis or file. Defaults to file.')->addArgument('cache-adapter', InputArgument::OPTIONAL, 'Cache adapter to use, redis or file. Optional, defaults to file based cache.', 'file')->addOption('config', 'c', InputArgument::OPTIONAL, 'File to get config parameters from.');
    }
    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['<info>Cleaning server resources</info>', '<info>=========================</info>', '']);
        $config = $input->getOption('config');
        if (!empty($config)) {
            Config::set($config);
        }
        $cacheAdapter = !empty($input->getArgument('cache-adapter')) ? $input->getArgument('cache-adapter') : 'file';
        $this->server = new TusServer(CacheFactory::make($cacheAdapter));
        $deleted = $this->server->handleExpiration();
        if (empty($deleted)) {
            $output->writeln('<comment>Nothing to delete.</comment>');
        } else {
            foreach ($deleted as $key => $item) {
                $output->writeln('<comment>' . ($key + 1) . ". Deleted {$item['name']} from " . dirname($item['file_path']) . '</comment>');
            }
        }
        $output->writeln('');
    }
}