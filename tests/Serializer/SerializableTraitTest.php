<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Tests\Serializer;

use Assert\AssertionFailedException;
use PB\Component\FirstAid\Tests\Serializer\Fake\FakeSerializable;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class SerializableTraitTest extends TestCase
{
    private const DATA = [
        'key-1' => [
            'key-1-1' => 'value-1-1',
            'key-1-2' => 'value-1-2',
            'key-1-3' => null,
            'key-1-4' => false,
            'key-1-5' => '',
            'key-1-6' => [],
        ],
        'key-2' => [
            'key-2-1' => 'value-2-1',
            'key-2-2' => [
                'key-2-2-1' => 'value-2-2-1',
                'key-2-2-2' => 'value-2-2-2',
            ],
        ],
    ];

    #######################################
    # SerializableTrait::assertKeyExist() #
    #######################################

    /**
     * @return array
     */
    public function assertKeyNotExistDataProvider(): array
    {
        return [
            [self::DATA, 'key-3', 'Missing required `key-3` key.'],
            [self::DATA, 'key-1.key-1-100', 'Missing required `key-1.key-1-100` key.'],
        ];
    }

    /**
     * @dataProvider assertKeyNotExistDataProvider
     *
     * @param array $data
     * @param string $key
     * @param string $expectedErrorMessage
     *
     * @throws AssertionFailedException
     */
    public function testShouldCallAssertKeyExistTraitMethodAndCheckIfAssertionIsWorkingCorrectForNotExistingKeys(
        array $data,
        string $key,
        string $expectedErrorMessage
    ): void {
        // Expect
        $this->expectException(AssertionFailedException::class);
        $this->expectExceptionMessage($expectedErrorMessage);

        // When
        FakeSerializable::callAssertKeyExist($data, $key);
    }

    /**
     * @return array
     */
    public function assertKeyExistDataProvider(): array
    {
        return [
            'string value' => [self::DATA, 'key-1.key-1-1'],
            'null value' => [self::DATA, 'key-1.key-1-3'],
            'false value' => [self::DATA, 'key-1.key-1-4'],
            'empty string value' => [self::DATA, 'key-1.key-1-5'],
            'empty array value' => [self::DATA, 'key-1.key-1-6'],
            'more nested key' => [self::DATA, 'key-2.key-2-2.key-2-2-1'],
        ];
    }

    /**
     * @dataProvider assertKeyExistDataProvider
     *
     * @param array $data
     * @param string $key
     */
    public function testShouldCallAssertKeyExistTraitMethodAndCheckIfAssertionIsWorkingCorrectForExistingKeys(
        array $data,
        string $key
    ): void {

        // When & Then
        try {
            FakeSerializable::callAssertKeyExist($data, $key);
            $this->assertTrue(true);
        } catch (AssertionFailedException $exception) {
            $this->fail('Assertion throw exception for key which exist in array.');
        }
    }

    #######
    # End #
    #######
}
