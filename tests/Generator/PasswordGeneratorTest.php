<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Tests\Generator;

use PB\Component\FirstAid\Generator\PasswordGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class PasswordGeneratorTest extends TestCase
{
    #################################
    # PasswordGenerator::generate() #
    #################################

    /**
     * @return array
     */
    public function generateDataProvider(): array
    {
        // Dataset 1
        $args1 = [];
        $isValid1 = true;
        $expectedErrorMessage1 = null;

        return [
            'default arguments' => [$args1, $isValid1, $expectedErrorMessage1],
        ];
    }

    /**
     * @dataProvider generateDataProvider
     *
     * @param array $args
     * @param bool $isValid
     * @param string|null $expectedErrorMessage
     */
    public function testShouldCallGenerateStaticMethodAndCheckIfReturnedStringIsCorrect(
        array $args,
        bool $isValid,
        ?string $expectedErrorMessage
    ): void {
        // Expect
        
        
        // Given
        $expectedLength = $args[0] ?? 16;
        $expectedUppercase = $args[1] ?? 2;
        $expectedDigits = $args[2] ?? 2;
        $expectedSpecialChars = $args[3] ?? 2;
        $expectedLowercase = $expectedLength - $expectedUppercase - $expectedDigits - $expectedSpecialChars;

        // When
        $actual = PasswordGenerator::generate(...$args);
        $actualLength = strlen($actual);
        $actualLowercase = preg_match_all('/[a-z]/', $actual);
        $actualUppercase = preg_match_all('/[A-Z]/', $actual);
        $actualDigits = preg_match_all('/[0-9]/', $actual);
        $actualSpecialChars = preg_match_all('/[-+_!@#$%^&*.,?]/', $actual);

        // Then
        $this->assertTrue(is_string($actual));
        $this->assertSame($expectedLength, $actualLength);
        $this->assertSame($expectedLowercase, $actualLowercase);
        $this->assertSame($expectedUppercase, $actualUppercase);
        $this->assertSame($expectedDigits, $actualDigits);
        $this->assertSame($expectedSpecialChars, $actualSpecialChars);
        $this->assertShuffle($actual, $expectedLowercase, $expectedUppercase, $expectedDigits, $expectedSpecialChars);
    }
    
    #######
    # End #
    #######

    /**
     * @param string $actual
     * @param int $expectedLowercase
     * @param int $expectedUppercase
     * @param int $expectedDigits
     * @param int $expectedSpecialChars
     */
    private function assertShuffle(string $actual, int $expectedLowercase, int $expectedUppercase, int $expectedDigits, int $expectedSpecialChars): void
    {
        // When
        $actualLowercaseString = substr($actual, 0, $expectedLowercase);
        $actualLowercaseCount = preg_match_all('/[a-z]/', $actualLowercaseString);
        $actualLowercaseIsShuffled = $expectedLowercase !== $actualLowercaseCount;

        $actualUppercaseString = substr($actual, $actualLowercaseCount, $expectedUppercase);
        $actualUppercaseCount = preg_match_all('/[A-Z]/', $actualUppercaseString);
        $actualUppercaseIsShuffled = $expectedUppercase !== $actualUppercaseCount;

        $actualDigitsString = substr($actual, $actualLowercaseCount + $actualUppercaseCount, $expectedDigits);
        $actualDigitsCount = preg_match_all('/[0-9]/', $actualDigitsString);
        $actualDigitsIsShuffled = $expectedDigits !== $actualDigitsCount;

        $actualSpecialCharsString = substr($actual, $actualLowercaseCount + $actualUppercaseCount + $actualDigitsCount, $expectedSpecialChars);
        $actualSpecialCharsCount = preg_match_all('/[-+_!@#$%^&*.,?]/', $actualSpecialCharsString);
        $actualSpecialCharsIsShuffled = $expectedSpecialChars !== $actualSpecialCharsCount;

        // Then
        $this->assertTrue($actualLowercaseIsShuffled && $actualUppercaseIsShuffled && $actualDigitsIsShuffled && $actualSpecialCharsIsShuffled);
    }
}
