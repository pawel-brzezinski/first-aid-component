<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Tests\Accessor\Fake;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class DolorClass extends IpsumClass
{
    protected string $value = 'value in dolor';

    private string $dolor = 'private dolor value';
}
