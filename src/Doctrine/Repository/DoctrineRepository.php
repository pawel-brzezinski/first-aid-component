<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Doctrine\Repository;

use Doctrine\ORM\{AbstractQuery, EntityManagerInterface, EntityRepository, NonUniqueResultException, QueryBuilder};
use PB\Component\FirstAid\Resource\Exception\NotFoundException;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
abstract class DoctrineRepository
{
    protected EntityManagerInterface $entityManager;

    protected EntityRepository $repository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->setEntityRepository($this->getRepositoryClassName());
    }

    /**
     *
     */
    public function apply(): void
    {
        $this->entityManager->flush();
    }

    /**
     * Returns entity repository classname.
     *
     * @return string
     */
    abstract protected function getRepositoryClassName(): string;

    /**
     * @param object $object
     */
    protected function register(object $object): void
    {
        $this->entityManager->persist($object);
        $this->apply();
    }

    /**
     * @param object $object
     */
    protected function delete(object $object): void
    {
        $this->entityManager->remove($object);
        $this->apply();
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return object|null
     *
     * @throws NonUniqueResultException
     * @throws NotFoundException
     */
    protected function oneOrException(QueryBuilder $queryBuilder): ?object
    {
        $object = $queryBuilder
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT)
        ;

        if (null === $object) {
            throw new NotFoundException();
        }

        return $object;
    }

    /**
     * @param string $classname
     */
    private function setEntityRepository(string $classname): void
    {
        /** @var EntityRepository $objectRepository */
        $objectRepository = $this->entityManager->getRepository($classname); /** @phpstan-ignore-line */
        $this->repository = $objectRepository;
    }
}
