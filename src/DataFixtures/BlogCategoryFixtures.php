<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\BlogCategoryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BlogCategoryFixtures extends Fixture implements FixtureGroupInterface
{
    use DataFixturesTrait;

    public static function getGroups(): array
    {
        return ['all'];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        BlogCategoryFactory::createSequence(
            [
                [
                    'id' => 1,
                    'name' => 'Cookery',
                    'active' => true,
                    'sortOrder' => 1,
                ],
                [
                    'id' => 2,
                    'name' => 'Fashion',
                    'active' => true,
                    'sortOrder' => 2,
                ],
                [
                    'id' => 3,
                    'name' => 'Food',
                    'active' => true,
                    'sortOrder' => 3,
                ],
                [
                    'id' => 4,
                    'name' => 'Home',
                    'active' => true,
                    'sortOrder' => 4,
                ],
                [
                    'id' => 5,
                    'name' => 'Leisure',
                    'active' => true,
                    'sortOrder' => 5,
                ],
                [
                    'id' => 6,
                    'name' => 'Technology',
                    'active' => true,
                    'sortOrder' => 6,
                ],
                [
                    'id' => 7,
                    'name' => 'Transport',
                    'active' => true,
                    'sortOrder' => 7,
                ],
                [
                    'id' => 8,
                    'name' => 'Travel',
                    'active' => true,
                    'sortOrder' => 8,
                ],
            ]
        );
    }
}
