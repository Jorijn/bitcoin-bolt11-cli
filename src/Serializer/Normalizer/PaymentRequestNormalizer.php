<?php

declare(strict_types=1);

namespace Jorijn\Bitcoin\Bolt11\Serializer\Normalizer;

use Jorijn\Bitcoin\Bolt11\Model\PaymentRequest;
use Jorijn\Bitcoin\Bolt11\Normalizer\PaymentRequestDenormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class PaymentRequestNormalizer implements DenormalizerInterface
{
    protected PaymentRequestDenormalizer $paymentRequestDenormalizer;

    public function __construct(PaymentRequestDenormalizer $paymentRequestDenormalizer)
    {
        $this->paymentRequestDenormalizer = $paymentRequestDenormalizer;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return is_a($type, PaymentRequest::class, true);
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): PaymentRequest
    {
        return $this->paymentRequestDenormalizer->denormalize($data);
    }
}
