# Demo Symfony MVC app
This application is a real world demonstration of Symfony MVC architecture with Twig/Webpack Encore/Bootstrap.
It implements most of the Symfony basic and advanced features. See the app home page for details.

See README_LOCAL_DEVELOPMENT.md for local development with Docker integration.

Using Doctrine data fixtures and Zenstruck/Foundry bundles, the application can be seeded with users, blog posts and blog post comments.

Code quality is maintained with PHPStan, PHP-CS-Fixer, and PHPUnit.

## Preparation
1. Create MySQL Database e.g. demo_symfony

2. Copy .env.dev.example to .env.dev.local and replace values for your environment.

3. Composer Install
```sh
composer install
```
4. npm install
```sh
npm install
```

5. Run migrations
```sh
symfony console doctrine:migrations:migrate
```

6. Seed the database
```sh
symfony console doctrine:fixtures:load
```

## Running the application
1. Start the services
```sh
symfony serve
```
If you are using Docker integration:
```sh
docker-compose up -d
```

```sh
symfony console messenger:consume async -vv
```

```sh
npm run watch
```

2. Navigate to http://localhost:8000


3. Login with a user created in user seeder: src/DataFixtures/UserFixtures. Default password is Demo1234 for all users:

## Code Quality Tools
1. php-cs-fixer.php. See php-cs-fixer.php for configuration
```sh
php vendor/bin/php-cs-fixer fix
```
2. PHPStan. See phpstan.neon for configuration
```sh
php vendor/bin/phpstan analyse
```

## License

The Demo Symfony MVC app is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
