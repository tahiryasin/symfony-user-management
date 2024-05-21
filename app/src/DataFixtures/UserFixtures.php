<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(ObjectManager $manager): void
    {
        $userData = [
            'Barack - Obama - White House',
            'Britney - Spears - America',
            'Leonardo - DiCaprio - Titanic',
        ];

        foreach ($userData as $data) {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->insert('user')
                ->setValue('data', ':data')
                ->setParameter('data', $data);

            $queryBuilder->execute();
        }
    }
}
