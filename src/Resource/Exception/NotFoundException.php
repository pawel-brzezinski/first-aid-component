<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Resource\Exception;

use Exception;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class NotFoundException extends Exception
{
    /**
     * NotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('Resource not found');
    }
}
