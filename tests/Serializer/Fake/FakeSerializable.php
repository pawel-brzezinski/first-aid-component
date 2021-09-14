<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Tests\Serializer\Fake;

use Assert\AssertionFailedException;
use PB\Component\FirstAid\Serializer\SerializableTrait;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class FakeSerializable
{
    use SerializableTrait;

    /**
     * @param array $data
     * @param string $key
     *
     * @throws AssertionFailedException
     */
    public static function callAssertKeyExist(array $data, string $key): void
    {
        self::assertKeyExist($data, $key);
    }
}
