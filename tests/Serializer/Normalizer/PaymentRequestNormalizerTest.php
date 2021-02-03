<?php

declare(strict_types=1);

namespace Tests\Jorijn\Bitcoin\Bolt11\Serializer\Normalizer;

use Jorijn\Bitcoin\Bolt11\Model\PaymentRequest;
use Jorijn\Bitcoin\Bolt11\Normalizer\PaymentRequestDenormalizer;
use Jorijn\Bitcoin\Bolt11\Serializer\Normalizer\PaymentRequestNormalizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Jorijn\Bitcoin\Bolt11\Serializer\Normalizer\PaymentRequestNormalizer
 * @covers ::__construct
 *
 * @internal
 */
final class PaymentRequestNormalizerTest extends TestCase
{
    /** @var MockObject|PaymentRequestDenormalizer */
    private $wrappedNormalizer;
    private PaymentRequestNormalizer $wrapperNormalizer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->wrappedNormalizer = $this->createMock(PaymentRequestDenormalizer::class);
        $this->wrapperNormalizer = new PaymentRequestNormalizer($this->wrappedNormalizer);
    }

    /**
     * @covers ::denormalize
     */
    public function testDenormalization(): void
    {
        $data = ['data_'.random_int(1000, 2000)];
        $returnValue = new PaymentRequest();

        $this->wrappedNormalizer
            ->expects(static::once())
            ->method('denormalize')
            ->with($data)
            ->willReturn($returnValue)
        ;

        static::assertSame(
            $returnValue,
            $this->wrapperNormalizer->denormalize($data, '')
        );
    }

    /**
     * @covers ::supportsDenormalization
     */
    public function testSupportsDenormalization(): void
    {
        static::assertTrue($this->wrapperNormalizer->supportsDenormalization('', PaymentRequest::class));
        static::assertFalse($this->wrapperNormalizer->supportsDenormalization('', \stdClass::class));
    }
}
