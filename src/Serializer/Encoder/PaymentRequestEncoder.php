<?php

declare(strict_types=1);

namespace Jorijn\Bitcoin\Bolt11\Serializer\Encoder;

use Jorijn\Bitcoin\Bolt11\Encoder\PaymentRequestDecoder;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class PaymentRequestEncoder implements DecoderInterface
{
    protected PaymentRequestDecoder $paymentRequestDecoder;

    public function __construct(PaymentRequestDecoder $paymentRequestDecoder)
    {
        $this->paymentRequestDecoder = $paymentRequestDecoder;
    }

    public function decode(string $data, string $format, array $context = []): array
    {
        return $this->paymentRequestDecoder->decode($data);
    }

    public function supportsDecoding(string $format)
    {
        return 'bolt11' === $format;
    }
}
