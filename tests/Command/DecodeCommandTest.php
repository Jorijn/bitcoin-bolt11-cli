<?php

declare(strict_types=1);

namespace Tests\Jorijn\Bitcoin\Bolt11\Command;

use Jorijn\Bitcoin\Bolt11\Command\DecodeCommand;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @coversDefaultClass \Jorijn\Bitcoin\Bolt11\Command\DecodeCommand
 * @covers ::__construct
 * @covers ::configure
 *
 * @internal
 */
final class DecodeCommandTest extends TestCase
{
    /** @var MockObject|SerializerInterface */
    protected $serializer;
    /** @var DecoderInterface|MockObject */
    protected $decoder;
    protected DecodeCommand $command;
    private string $paymentRequest;
    private array $decodedData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->decoder = $this->createMock(DecoderInterface::class);
        $this->command = new DecodeCommand($this->decoder, $this->serializer);

        $this->paymentRequest = 'pr'.random_int(100, 200);
        $this->decodedData = ['decoded' => ['data' => random_int(100, 200)]];
    }

    /**
     * @covers ::execute
     */
    public function testOutputIsUnsupportedFormat(): void
    {
        $commandTester = $this->createCommandTester();
        $commandTester->execute([
            'command' => $this->command->getName(),
            'payment-request' => 'pr',
            '--output-format' => 'does_not_exist',
        ]);

        static::assertSame(1, $commandTester->getStatusCode());
        static::assertStringContainsString(
            'does_not_exist is not in the list of supported formats',
            $commandTester->getDisplay()
        );
    }

    public function providerOfDecodeScenarios(): array
    {
        return [
            'xml, not formatted' => ['xml', false],
            'xml, formatted' => ['xml', true],
            'json, not formatted' => ['json', false],
            'json, formatted' => ['json', true],
            'yaml, not formatted' => ['yaml', false],
            'yaml, formatted' => ['yaml', true],
            'csv, not formatted' => ['csv', false],
            'csv, formatted' => ['csv', true],
            'unspecified, not formatted' => [null, false],
            'unspecified, formatted' => [null, true],
        ];
    }

    /**
     * @dataProvider providerOfDecodeScenarios
     * @covers ::execute
     */
    public function testMatrix(?string $format, bool $formatted): void
    {
        $serializerOutput = 'so'.random_int(100, 200);

        $arguments = [
            'command' => $this->command->getName(),
            'payment-request' => $this->paymentRequest,
        ];

        if ($formatted) {
            $arguments['--formatted'] = true;
        }

        if ($format) {
            $arguments['--output-format'] = $format;
        }

        $this->decoder
            ->expects(static::once())
            ->method('decode')
            ->with($this->paymentRequest, 'bolt11')
            ->willReturn($this->decodedData)
        ;

        $this->serializer
            ->expects(static::once())
            ->method('serialize')
            ->with(
                $this->decodedData,
                $format ?? DecodeCommand::DEFAULT_OUTPUT_FORMAT,
                $formatted ? $this->getContextForFormat($format ?? DecodeCommand::DEFAULT_OUTPUT_FORMAT) : []
            )
            ->willReturn($serializerOutput)
        ;

        $commandTester = $this->createCommandTester();
        $commandTester->execute($arguments);

        static::assertSame($serializerOutput, $commandTester->getDisplay());
    }

    protected function createCommandTester(): CommandTester
    {
        $application = new Application();
        $application->add($this->command->setName('decode'));

        return new CommandTester($this->command);
    }

    private function getContextForFormat(string $format): array
    {
        switch ($format) {
            case 'json':
                return ['json_encode_options' => JSON_PRETTY_PRINT];

            case 'xml':
                return ['xml_format_output' => true];

            case 'yaml':
                return ['yaml_indent' => true, 'yaml_inline' => 10];

            default:
                return [];
        }
    }
}
