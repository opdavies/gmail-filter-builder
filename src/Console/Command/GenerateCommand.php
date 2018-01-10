<?php

namespace Opdavies\GmailFilterBuilder\Console\Command;

use Opdavies\GmailFilterBuilder\Builder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
                new InputArgument('input-file', InputArgument::OPTIONAL, 'The name of the PHP file containing your filters.', 'filters.php'),
                new InputArgument('output-file', InputArgument::OPTIONAL, 'The name of the XML file to generate.', 'filters.xml')
            ])
            ->setDescription('Generates XML for Gmail filters.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputFile = $input->getArgument('input-file');
        $outputFile = $input->getArgument('output-file');

        if (file_exists(__DIR__.'/../../../../'.$inputFile)) {
            $filters = require(__DIR__.'/../../../../'.$inputFile);
        } elseif (file_exists(__DIR__.'/../../../'.$inputFile)) {
            $filters = require(__DIR__.'/../../../'.$inputFile);
        } else {
            throw new \Exception('No filters.php file found.');
        }

        echo new Builder($filters);
    }
}
