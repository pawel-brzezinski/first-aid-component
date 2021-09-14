<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Serializer;

use Adbar\Dot;
use Assert\{Assertion, AssertionFailedException};

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
trait SerializableTrait
{
    /**
     * @param array $data
     * @param string $key
     *
     * @throws AssertionFailedException
     */
    private static function assertKeyExist(array $data, string $key): void
    {
        $dot = new Dot($data);
        Assertion::true($dot->has($key), sprintf('Missing required `%s` key.', $key));
    }
}
