<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Tests\Doctrine\Repository\Fake;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use PB\Component\FirstAid\Doctrine\Repository\DoctrineRepository;
use PB\Component\FirstAid\Resource\Exception\NotFoundException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class FakeDoctrineRepository extends DoctrineRepository
{
    /**
     * @param object $object
     */
    public function callRegister(object $object): void
    {
        $this->register($object);
    }

    /**
     * @param object $object
     */
    public function callDelete(object $object): void
    {
        $this->delete($object);
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return object|null
     *
     * @throws NonUniqueResultException
     * @throws NotFoundException
     */
    public function callOneOrException(QueryBuilder $queryBuilder): ?object
    {
        return $this->oneOrException($queryBuilder);
    }

    /**
     * @return string
     */
    protected function getRepositoryClassName(): string
    {
        return 'FakeRepository';
    }
}
