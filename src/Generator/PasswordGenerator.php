<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Generator;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class PasswordGenerator
{
    private const DEFAULT_DIGITS = 2;
    private const DEFAULT_LENGTH = 16;
    private const DEFAULT_SPECIAL_CHARS = 2;
    private const DEFAULT_UPPERCASE_LETTERS = 2;

    private const DIGIT_CHARS = '0123456789';
    private const LOWERCASE_CHARS = 'abcdefghijklmnopqrstuvwxyz';
    private const SPECIAL_CHARS = '-+_!@#$%^&*.,?';
    private const UPPERCASE_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @param int $length
     * @param int $uppercase
     * @param int $digits
     * @param int $specialChars
     *
     * @return string
     */
    public static function generate(
        int $length = self::DEFAULT_LENGTH,
        int $uppercase = self::DEFAULT_UPPERCASE_LETTERS,
        int $digits = self::DEFAULT_DIGITS,
        int $specialChars = self::DEFAULT_SPECIAL_CHARS
    ): string {
        $lowercase = $length - $uppercase - $digits - $specialChars;

        $passwordChars = array_merge(
            self::randomChars($lowercase, self::LOWERCASE_CHARS),
            self::randomChars($uppercase, self::UPPERCASE_CHARS),
            self::randomChars($digits, self::DIGIT_CHARS),
            self::randomChars($specialChars, self::SPECIAL_CHARS)
        );

        shuffle($passwordChars);

        return implode('', $passwordChars);
    }

    /**
     * @param int $count
     * @param string $charsRange
     *
     * @return array
     */
    private static function randomChars(int $count, string $charsRange): array
    {
        $chars = [];

        for ($i = 1; $i <= $count; $i ++) {
            $chars[] = self::randomChar($charsRange);
        }

        return $chars;
    }

    /**
     * @param string $charsRange
     *
     * @return string
     */
    private static function randomChar(string $charsRange): string
    {
        $chars = str_split($charsRange);

        return $chars[array_rand($chars)];
    }
}
