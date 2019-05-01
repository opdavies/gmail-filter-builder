<?php

namespace Opdavies\GmailFilterBuilder\Console\Command;

use Opdavies\GmailFilterBuilder\Service\Builder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

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
            ->setDescription('Generates XML for Gmail filters.')
            ->addOption('input-file', 'i', InputOption::VALUE_OPTIONAL, 'The name of the PHP file containing your filters.', 'filters.php')
            ->addOption('output-file', 'o', InputOption::VALUE_OPTIONAL, 'The name of the XML file to generate.')
            ->addOption('expanded', 'e', InputOption::VALUE_NONE, 'Whether to generate expanded XML.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            // TODO: Inject this.
            new Builder($this->filters($input), $outputFile = $this->outputFile($input), true, $input->getOption('expanded'));

            $io->success(sprintf('%s file generated.', $outputFile));
        } catch (IOException $e) {
            $io->error($e->getMessage());
        }
    }

    private function outputFile(InputInterface $input): string
    {
        return $input->getOption('output-file') ?? vsprintf('%s/%s.xml', [
            getcwd(),
            pathinfo($input->getOption('input-file'))['filename'],
        ]);
    }

    private function filters(InputInterface $input): array
    {
        $fs = new Filesystem();

        if (!$fs->exists($inputFile = $input->getOption('input-file') ?? getcwd() . '/filters.php')) {
            throw new \RuntimeException('No input file found.');
        }

        return require $inputFile;
    }
}
