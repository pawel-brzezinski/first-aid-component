<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Tests\Assertion;

use Assert\AssertionFailedException;
use PB\Component\FirstAid\Assertion\Assertion;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class AssertionTest extends TestCase
{
    #########################
    # Assertion::password() #
    #########################

    /**
     * @return array
     */
    public function passwordDataProvider(): array
    {
        $defErrorMsg = 'The password does not meet the criteria. A minimum of %d characters is required, at least one uppercase, one lowercase, one digit and one special character.';

        // Dataset 1
        $value1 = 'Too_short1';
        $minLength1 = 11;
        $args1 = [$value1, $minLength1];
        $expected1 = false;
        $expectedErrorMsg1 = sprintf($defErrorMsg, $minLength1);

        // Dataset 2
        $value2 = 'no_uppercase2';
        $minLength2 = 13;
        $args2 = [$value2, $minLength2];
        $expected2 = false;
        $expectedErrorMsg2 = sprintf($defErrorMsg, $minLength2);

        // Dataset 3
        $value3 = 'NO_LOWERCASE3';
        $minLength3 = 13;
        $args3 = [$value3, $minLength3];
        $expected3 = false;
        $expectedErrorMsg3 = sprintf($defErrorMsg, $minLength3);

        // Dataset 4
        $value4 = 'No_digit';
        $minLength4 = 8;
        $args4 = [$value4, $minLength4];
        $expected4 = false;
        $expectedErrorMsg4 = sprintf($defErrorMsg, $minLength4);

        // Dataset 5
        $value5 = 'No special char 5';
        $minLength5 = 17;
        $args5 = [$value5, $minLength5];
        $expected5 = false;
        $expectedErrorMsg5 = sprintf($defErrorMsg, $minLength5);

        // Dataset 6
        $value6 = 'Custom string error message';
        $minLength6 = 100;
        $message6 = 'Too short. Need %d chars.';
        $args6 = [$value6, $minLength6, $message6];
        $expected6 = false;
        $expectedErrorMsg6 = sprintf($message6, $minLength6);

        // Dataset 7
        $value7 = 'Custom callable error message';
        $minLength7 = 100;
        $message7 = function() {
            return 'Error message from callable';
        };
        $args7 = [$value7, $minLength7, $message7];
        $expected7 = false;
        $expectedErrorMsg7 = 'Error message from callable';

        // Dataset 8
        $value8 = 'Custom property path';
        $minLength8 = 100;
        $propertyPath8 = 'password';
        $args8 = [$value8, $minLength8, null, $propertyPath8];
        $expected8 = false;
        $expectedErrorMsg8 = sprintf($defErrorMsg, $minLength8);

        // Dataset 9
        $value9 = 'Valid 9$';
        $minLength9 = 8;
        $args9 = [$value9, $minLength9];
        $expected9 = true;
        $expectedErrorMsg9 = null;
        
        return [
            'value is too short, no custom attributes' => [$args1, $expected1, $expectedErrorMsg1],
            'value does not have uppercase letter, no custom attributes' => [$args2, $expected2, $expectedErrorMsg2],
            'value does not have lowercase letter, no custom attributes' => [$args3, $expected3, $expectedErrorMsg3],
            'value does not have digit, no custom attributes' => [$args4, $expected4, $expectedErrorMsg4],
            'value does not special char, no custom attributes' => [$args5, $expected5, $expectedErrorMsg5],
            'value is too short, custom string error message' => [$args6, $expected6, $expectedErrorMsg6],
            'value is too short, custom callable error message' => [$args7, $expected7, $expectedErrorMsg7],
            'value is too short, custom property path' => [$args8, $expected8, $expectedErrorMsg8],
            'value is valid' => [$args9, $expected9, $expectedErrorMsg9],
        ];
    }

    /**
     * @dataProvider passwordDataProvider
     *
     * @param array $args
     * @param bool $expected
     * @param string|null $expectedErrorMsg
     */
    public function testShouldCallPasswordStaticMethodAndCheckIfAssertionHasBeenResolvedCorrectly(
        array $args,
        bool $expected,
        ?string $expectedErrorMsg
    ): void {
        // Expect
        if (null !== $expectedErrorMsg) {
            $this->expectException(AssertionFailedException::class);
            $this->expectExceptionMessage($expectedErrorMsg);
        }

        // Given


        // When & Then
        try {
            $actual = Assertion::password(...$args);

            $this->assertSame($expected, $actual);
        } catch (AssertionFailedException $assertionFailedException) {
            $expectedValue = $args[0];
            $expectedCode = 1000;
            $expectedPropertyPath = $args[3] ?? null;
            $expectedConstraints = ['min_length' => $args[1]];

            $this->assertSame($expectedValue, $assertionFailedException->getValue());
            $this->assertSame($expectedCode, $assertionFailedException->getCode());
            $this->assertSame($expectedPropertyPath, $assertionFailedException->getPropertyPath());
            $this->assertSame($expectedConstraints, $assertionFailedException->getConstraints());

            throw $assertionFailedException;
        }
    }

    #######
    # End #
    #######
}
