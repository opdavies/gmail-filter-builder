<?php

namespace Opdavies\GmailFilterBuilder\Console\Command;

use Opdavies\GmailFilterBuilder\Builder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOException;

class GenerateCommand extends Command
{
    const NAME = 'generate';

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDefinition([
                new InputOption('input-file', null, InputOption::VALUE_OPTIONAL, 'The name of the PHP file containing your filters.', 'filters.php'),
                new InputOption('output-file', null, InputOption::VALUE_OPTIONAL, 'The name of the XML file to generate.', 'filters.xml')
            ])
            ->setDescription('Generates XML for Gmail filters.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputFile = $input->getOption('input-file');
        $outputFile = $input->getOption('output-file');

        if (file_exists($inputFile)) {
            $filters = require_once $inputFile;
        } elseif (file_exists(__DIR__.'/'.$inputFile)) {
            $filters = require_once __DIR__.'/'.$inputFile;
        } elseif (file_exists(__DIR__.'/../../../../../../'.$inputFile)) {
            # Installed as a dependency within "vendor".
            $filters = require_once __DIR__.'/../../../../../../'.$inputFile;
        } else {
            throw new \Exception('No filters.php file found.');
        }

        $io = new SymfonyStyle($input, $output);

        try {
            new Builder($filters, $outputFile);

            $io->success(sprintf('%s file generated.', $outputFile));
        } catch (IOException $e) {
            $io->error($e->getMessage());
        }
    }
}
