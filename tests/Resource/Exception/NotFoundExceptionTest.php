<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Tests\Resource\Exception;

use PB\Component\FirstAid\Resource\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class NotFoundExceptionTest extends TestCase
{
    ####################################
    # NotFoundException::__construct() #
    ####################################

    /**
     * @throws NotFoundException
     */
    public function testShouldThrowExceptionAndCheckIfMessageIsCorrect(): void
    {
        // Expect
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Resource not found');

        // When
        throw new NotFoundException();
    }

    #######
    # End #
    #######
}
