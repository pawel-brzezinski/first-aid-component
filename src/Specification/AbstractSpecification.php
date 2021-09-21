<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Specification;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class AbstractSpecification
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    abstract public function isSatisfiedBy($value): bool;
}
