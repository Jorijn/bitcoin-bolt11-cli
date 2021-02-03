<?php

declare(strict_types=1);

namespace Tests\Jorijn\Bitcoin\Bolt11\Serializer\Encoder;

use Jorijn\Bitcoin\Bolt11\Encoder\PaymentRequestDecoder;
use Jorijn\Bitcoin\Bolt11\Serializer\Encoder\PaymentRequestEncoder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Jorijn\Bitcoin\Bolt11\Serializer\Encoder\PaymentRequestEncoder
 * @covers ::__construct
 *
 * @internal
 */
final class PaymentRequestEncoderTest extends TestCase
{
    /** @var MockObject|PaymentRequestDecoder */
    private $wrappedEncoder;
    private PaymentRequestEncoder $wrapperEncoder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->wrappedEncoder = $this->createMock(PaymentRequestDecoder::class);
        $this->wrapperEncoder = new PaymentRequestEncoder($this->wrappedEncoder);
    }

    /**
     * @covers ::decode
     */
    public function testDecode(): void
    {
        $returnValue = ['return_value' => random_int(1000, 2000)];
        $data = 'input_data_'.random_int(100, 200);

        $this->wrappedEncoder
            ->expects(static::once())
            ->method('decode')
            ->with($data)
            ->willReturn($returnValue)
        ;

        static::assertSame(
            $returnValue,
            $this->wrapperEncoder->decode($data, 'bolt11')
        );
    }

    /**
     * @covers ::supportsDecoding
     */
    public function testSupportsDecoding(): void
    {
        static::assertTrue($this->wrapperEncoder->supportsDecoding('bolt11'));
        static::assertFalse($this->wrapperEncoder->supportsDecoding('something_else'));
    }
}
