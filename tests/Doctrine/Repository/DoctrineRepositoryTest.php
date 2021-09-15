<?php

declare(strict_types=1);

namespace PB\Component\FirstAid\Tests\Doctrine\Repository;

use Doctrine\ORM\{AbstractQuery, EntityManagerInterface, EntityRepository, NonUniqueResultException, QueryBuilder};
use PB\Component\FirstAid\Reflection\ReflectionHelper;
use PB\Component\FirstAid\Resource\Exception\NotFoundException;
use PB\Component\FirstAid\Tests\Doctrine\Repository\Fake\FakeDoctrineRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\{MethodProphecy, ObjectProphecy};
use ReflectionException;
use stdClass;

/**
 * @author Paweł Brzeziński <pawel.brzezinski@smartint.pl>
 */
final class DoctrineRepositoryTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy|EntityManagerInterface|null  */
    protected $emMock;

    /** @var ObjectProphecy|EntityRepository|null  */
    protected $repoMock;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->emMock = $this->prophesize(EntityManagerInterface::class);
        $this->repoMock = $this->prophesize(EntityRepository::class);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        $this->emMock = null;
        $this->repoMock = null;
    }

    #####################################
    # DoctrineRepository::__construct() #
    #####################################

    /**
     * @throws ReflectionException
     */
    public function testShouldCreateDoctrineRepositoryInstanceAndCheckIfPropertiesHasBeenSetCorrectly(): void
    {
        // Expect


        // Given
        $this->mockSetEntityRepository();

        // When
        $actual = $this->createRepository();

        // Then
        $this->assertSame($this->emMock->reveal(), ReflectionHelper::findPropertyValue($actual, 'entityManager'));
        $this->assertSame($this->repoMock->reveal(), ReflectionHelper::findPropertyValue($actual, 'repository'));
    }

    #######
    # End #
    #######

    ###############################
    # DoctrineRepository::apply() #
    ###############################

    /**
     *
     */
    public function testShouldCallApplyMethodAndCheckIfEntityManagerHasBennFlushed(): void
    {
        // Given
        $this->mockSetEntityRepository();

        // Mock EntityManagerInterface::flush()
        $this->emMock->flush()->shouldBeCalledOnce();
        // End

        // When
        $this->createRepository()->apply();
    }

    #######
    # End #
    #######

    ##################################
    # DoctrineRepository::register() #
    ##################################

    /**
     *
     */
    public function testShouldCallRegisterMethodAndCheckIfObjectHasBeenPersistedAndApplied(): void
    {
        // Given
        $object = new stdClass();

        $this->mockSetEntityRepository();

        // Mock EntityManagerInterface::persist()
        $this->emMock->persist($object)->shouldBeCalledOnce();
        // End

        // Mock EntityManagerInterface::flush()
        $this->emMock->flush()->shouldBeCalledOnce();
        // End

        // When
        $this->createRepository()->callRegister($object);
    }

    #######
    # End #
    #######

    ################################
    # DoctrineRepository::delete() #
    ################################

    /**
     *
     */
    public function testShouldCallDeleteMethodAndCheckIfObjectHasBeenRemovedAndApplied(): void
    {
        // Given
        $object = new stdClass();

        $this->mockSetEntityRepository();

        // Mock EntityManagerInterface::persist()
        $this->emMock->remove($object)->shouldBeCalledOnce();
        // End

        // Mock EntityManagerInterface::flush()
        $this->emMock->flush()->shouldBeCalledOnce();
        // End

        // When
        $this->createRepository()->callDelete($object);
    }

    #######
    # End #
    #######

    ########################################
    # DoctrineRepository::oneOrException() #
    ########################################

    /**
     * @return array
     */
    public function oneOrExceptionDataProvider(): array
    {
        return [
            'query returns object' => [new stdClass()],
            'query returns null' => [null],
        ];
    }

    /**
     * @dataProvider oneOrExceptionDataProvider
     *
     * @param object|null $queryResult
     *
     * @throws NonUniqueResultException
     * @throws NotFoundException
     */
    public function testShouldCallOneOrExceptionMethodAndCheckIfCorrectQueryHasBeenExecutedAndCorrectValueHasBeenReturned(?object $queryResult): void
    {
        // Expect
        if (null === $queryResult) {
            $this->expectException(NotFoundException::class);
        }

        // Given
        /** @var ObjectProphecy|QueryBuilder $qbMock */
        $qbMock = $this->prophesize(QueryBuilder::class);
        /** @var ObjectProphecy|AbstractQuery $qMock */
        $qMock = $this->prophesize(AbstractQuery::class);

        $this->mockSetEntityRepository();

        // Mock AbstractQuery::getOneOrNullResult()
        /** @var MethodProphecy $getQueryProp */
        $getQueryProp = $qbMock->getQuery();
        $getQueryProp->shouldBeCalledOnce()->willReturn($qMock->reveal());
        // End

        // Mock QueryBuilder::getQuery()
        $qMock->getOneOrNullResult(1)->shouldBeCalledOnce()->willReturn($queryResult);
        // End

        // When
        $actual = $this->createRepository()->callOneOrException($qbMock->reveal());

        // Then
        if (null !== $queryResult) {
            $this->assertSame($queryResult, $actual);
        }
    }

    #######
    # End #
    #######

    /**
     * @return FakeDoctrineRepository
     */
    private function createRepository(): FakeDoctrineRepository
    {
        return new FakeDoctrineRepository($this->emMock->reveal());
    }

    /**
     *
     */
    private function mockSetEntityRepository(): void
    {
        // Mock EntityManagerInterface::getRepository()
        $this->emMock->getRepository('FakeRepository')->shouldBeCalledTimes(1)->willReturn($this->repoMock->reveal());
        // End
    }
}
