<?php

declare(strict_types=1);

namespace Jorijn\Bitcoin\Bolt11\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class DecodeCommand extends Command
{
    public const SUPPORTED_FORMATS = ['xml', 'yaml', 'csv', 'json'];
    public const DEFAULT_OUTPUT_FORMAT = 'json';

    protected SerializerInterface $serializer;

    protected DecoderInterface $decoder;

    public function __construct(DecoderInterface $decoder, SerializerInterface $serializer)
    {
        parent::__construct(null);

        $this->decoder = $decoder;
        $this->serializer = $serializer;
    }

    public function configure(): void
    {
        $this
            ->setDescription('Decodes lightning network payment requests as defined in BOLT #11')
            ->addArgument('payment-request', InputArgument::REQUIRED, 'The payment request that should be decoded.')
            ->addOption(
                'output-format',
                'o',
                InputOption::VALUE_REQUIRED,
                'The format the payment request should be output as. Default: '.self::DEFAULT_OUTPUT_FORMAT.'. Supported: '.implode(
                    ', ',
                    self::SUPPORTED_FORMATS
                )
            )
            ->addOption('formatted', 'f', InputOption::VALUE_NONE, 'If supplied, will format the result.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $normalized = $this->decoder->decode($input->getArgument('payment-request'), 'bolt11');
        $format = $input->getOption('output-format') ?? self::DEFAULT_OUTPUT_FORMAT;

        if (!\in_array($format, self::SUPPORTED_FORMATS, true)) {
            $io->error(sprintf(
                '%s is not in the list of supported formats: %s',
                $format,
                implode(', ', self::SUPPORTED_FORMATS)
            ));

            return 1;
        }

        if ($input->getOption('formatted')) {
            switch ($format) {
                case 'json':
                    $context = ['json_encode_options' => JSON_PRETTY_PRINT];

                    break;

                case 'xml':
                    $context = ['xml_format_output' => true];

                    break;

                case 'yaml':
                    $context = ['yaml_indent' => true, 'yaml_inline' => 10];

                    break;

                default:
                    $context = [];
            }
        }

        $io->write($this->serializer->serialize($normalized, $format, $context ?? []));

        return 0;
    }
}
