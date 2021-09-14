<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Tests\Accessor\Fake;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
class IpsumClass extends LoremClass
{
    protected string $value = 'value in ipsum';

    private string $ipsum = 'private ipsum value';
}
