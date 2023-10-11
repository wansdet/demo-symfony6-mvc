<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['user', 'all'];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $beginDays = 365;

        // Create internal users
        UserFactory::createSequence(
            [
                [
                    'email' => 'admin1@example.com',
                    'firstName' => 'Jane',
                    'lastName' => 'Richards',
                    'jobTitle' => 'IT Support Manager',
                    'roles' => [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN, User::ROLE_EDITOR, User::ROLE_MODERATOR],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.$beginDays.' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.$beginDays.' days', '+ 0 day'),
                ],
                [
                    'email' => 'admin2@example.com',
                    'firstName' => 'David',
                    'lastName' => 'Williams',
                    'jobTitle' => 'IT Support Administrator',
                    'roles' => [User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN, User::ROLE_EDITOR],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.$beginDays.' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.$beginDays.' days', '+ 0 day'),
                ],
                [
                    'email' => 'editor1@example.com',
                    'firstName' => 'Lizzie',
                    'lastName' => 'Jones',
                    'jobTitle' => 'Chief Editor',
                    'roles' => [User::ROLE_ADMIN, User::ROLE_EDITOR],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 1).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 1).' days', '+ 0 day'),
                ],
                [
                    'email' => 'editor2@example.com',
                    'firstName' => 'Kevin',
                    'lastName' => 'McDonald',
                    'jobTitle' => 'Editor',
                    'roles' => [User::ROLE_ADMIN, User::ROLE_EDITOR],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 1).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 1).' days', '+ 0 day'),
                ],
                [
                    'email' => 'moderator1@example.com',
                    'firstName' => 'Kelly',
                    'lastName' => 'Stephens',
                    'jobTitle' => 'Moderator',
                    'roles' => [User::ROLE_ADMIN, User::ROLE_MODERATOR],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 2).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 2).' days', '+ 0 day'),
                ],
                [
                    'email' => 'moderator2@example.com',
                    'firstName' => 'Richard',
                    'lastName' => 'Harris',
                    'jobTitle' => 'Moderator',
                    'roles' => [User::ROLE_ADMIN, User::ROLE_MODERATOR],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 2).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 2).' days', '+ 0 day'),
                ],
                [
                    'email' => 'blogauthor1@example.com',
                    'firstName' => 'Robert',
                    'lastName' => 'Walker',
                    'jobTitle' => 'Blog Post Author',
                    'roles' => [User::ROLE_ADMIN, User::ROLE_BLOGGER],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 5).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 5).' days', '+ 0 day'),
                ],
                [
                    'email' => 'blogauthor2@example.com',
                    'firstName' => 'Venessa',
                    'lastName' => 'Hall',
                    'jobTitle' => 'Blog Post Author',
                    'roles' => [User::ROLE_ADMIN, User::ROLE_BLOGGER],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 5).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 5).' days', '+ 0 day'),
                ],
                [
                    'email' => 'blogauthor3@example.com',
                    'firstName' => 'Karen',
                    'lastName' => 'Young',
                    'jobTitle' => 'Blog Post Author',
                    'roles' => [User::ROLE_ADMIN, User::ROLE_BLOGGER],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 5).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 5).' days', '+ 0 day'),
                ],
                [
                    'email' => 'blogauthor4@example.com',
                    'firstName' => 'Madeleine',
                    'lastName' => 'Allen',
                    'jobTitle' => 'Blog Post Author',
                    'roles' => [User::ROLE_ADMIN, User::ROLE_BLOGGER],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 5).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 5).' days', '+ 0 day'),
                ],
                [
                    'email' => 'blogauthor5@example.com',
                    'firstName' => 'Bethany',
                    'lastName' => 'Harris',
                    'jobTitle' => 'Blog Post Author',
                    'roles' => [User::ROLE_ADMIN, User::ROLE_BLOGGER],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 5).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 5).' days', '+ 0 day'),
                ],
                [
                    'email' => 'user1@example.com',
                    'firstName' => 'Mary',
                    'lastName' => 'Smith',
                    'roles' => [User::ROLE_USER],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 10).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 10).' days', '+ 0 day'),
                ],
                [
                    'email' => 'user2@example.net',
                    'firstName' => 'John',
                    'lastName' => 'Richards',
                    'roles' => [User::ROLE_USER],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 11).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 11).' days', '+ 0 day'),
                ],
                [
                    'email' => 'user.3@example.org',
                    'firstName' => 'Catherine',
                    'lastName' => 'Jones',
                    'roles' => [User::ROLE_USER],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 12).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 12).' days', '+ 0 day'),
                ],
                [
                    'email' => 'user.4@example.com',
                    'firstName' => 'Christopher',
                    'lastName' => 'Parry',
                    'roles' => [User::ROLE_USER],
                    'status' => User::STATUS_ON_HOLD,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 13).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 13).' days', '+ 0 day'),
                ],
                [
                    'email' => 'user.5@example.net',
                    'firstName' => 'Theresa',
                    'lastName' => 'McDonald',
                    'roles' => [User::ROLE_USER],
                    'status' => User::STATUS_SUSPENDED,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 14).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 14).' days', '+ 0 day'),
                ],
                [
                    'email' => 'user.6@example.com',
                    'firstName' => 'Nicholas',
                    'lastName' => 'Morris',
                    'roles' => [User::ROLE_USER],
                    'status' => User::STATUS_ACTIVE,
                    'createdAt' => $faker->dateTimeInInterval('-'.($beginDays - 15).' days', '+ 0 day'),
                    'updatedAt' => $faker->dateTimeInInterval('-'.($beginDays - 15).' days', '+ 0 day'),
                ],
            ]
        );

        $totalOtherUsers = 59;
        $otherUsersBeginDays = 335;

        // Create other users
        UserFactory::createSequence(
            static function () use ($faker, $otherUsersBeginDays, $totalOtherUsers) {
                foreach (range(7, $totalOtherUsers) as $i) {
                    $sexSelect = $faker->randomElement(['male', 'female']);

                    if ('male' === $sexSelect) {
                        $firstName = $faker->firstNameMale();
                    } else {
                        $firstName = $faker->firstNameFemale();
                    }

                    $lastName = $faker->lastName();
                    $email = strtolower('user.'.$i.'@'.$faker->safeEmailDomain());
                    $createdAtDate = $faker->dateTimeInInterval('-'.($otherUsersBeginDays - $i).' days', '+ 0 day');

                    yield [
                        'email' => $email,
                        'firstName' => $firstName,
                        'lastName' => $lastName,
                        'roles' => ['ROLE_USER'],
                        'status' => User::STATUS_ACTIVE,
                        'createdAt' => $createdAtDate,
                        'updatedAt' => $createdAtDate,
                    ];
                }
            }
        );
    }
}
