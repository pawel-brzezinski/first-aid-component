<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Assertion;

use Assert\Assertion as BaseAssertion;
use Assert\AssertionFailedException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class Assertion extends BaseAssertion
{
    const INVALID_PASSWORD = 1000;

    private const PASSWORD_ERROR_MESSAGE = 'The password does not meet the criteria. A minimum of %d characters is required, at least one uppercase, one lowercase, one digit and one special character.';

    /**
     * @param string $value
     * @param int $minLength
     * @param string|callable|null $message
     * @param string|null $propertyPath
     * @return bool
     */
    public static function password(string $value, int $minLength, $message = null, string $propertyPath = null): bool
    {
        try {
            // Step 1. Check value length
            self::minLength($value, $minLength);

            // Step2. Check at least one uppercase
            self::regex($value, '/(?=.*[A-Z])/');

            // Step3. Check at least one lowercase
            self::regex($value, '/(?=.*[a-z])/');

            // Step4. Check at least one digit
            self::regex($value, '/(?=.*\d)/');

            // Step5. Check at least one special char
            self::regex($value, '/(?=.*[-+_!@#$%^&*.,?])/');
        } catch (AssertionFailedException $exception) {
            $message = sprintf(static::generateMessage($message ?: self::PASSWORD_ERROR_MESSAGE), $minLength);

            throw static::createException(
                $value,
                $message,
                static::INVALID_PASSWORD,
                $propertyPath,
                ['min_length' => $minLength]
            );
        }

        return true;
    }
}
