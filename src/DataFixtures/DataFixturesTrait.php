<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use Symfony\Component\String\AbstractString;
use Symfony\Component\String\UnicodeString;

use function Symfony\Component\String\u;

trait DataFixturesTrait
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->faker->seed(1234);
    }

    public function generateParagraphs(
        string $format = 'html',
        int $paragraphMin = 6,
        int $paragraphMax = 7,
        int $sentencesMin = 7,
        int $sentencesMax = 9
    ): string {
        $paragraphs = [];

        $paragraphCount = $this->faker->numberBetween($paragraphMin, $paragraphMax);
        $sentences = $this->faker->numberBetween($sentencesMin, $sentencesMax);

        for ($i = 0; $i < $paragraphCount; ++$i) {
            if ('html' === $format) {
                $paragraphs[] = '<p>'.$this->faker->paragraph($sentences).'</p>';
            } else {
                $paragraphs[] = $this->faker->paragraph($sentences);
            }
        }

        return implode("\n\n", $paragraphs);
    }

    /**
     * @return string|array<string>
     */
    public function generateSentences(
        int $sentencesMin = 1,
        int $sentencesMax = 2
    ): string|array {
        $sentences = $this->faker->numberBetween($sentencesMin, $sentencesMax);

        return $this->faker->sentences($sentences, true);
    }

    public function getRandomText(int $maxLength = 255): AbstractString|UnicodeString
    {
        $phrases = $this->getPhrases();
        shuffle($phrases);

        do {
            $text = u('. ')->join($phrases)->append('.');
            array_pop($phrases);
        } while ($text->length() > $maxLength);

        return $text;
    }

    /**
     * @return string[]
     */
    private function getPhrases(): array
    {
        return [
            'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'Pellentesque vitae velit ex',
            'Mauris dapibus risus quis suscipit vulputate',
            'Eros diam egestas libero eu vulputate risus',
            'In hac habitasse platea dictumst',
            'Morbi tempus commodo mattis',
            'Ut suscipit posuere justo at vulputate',
            'Ut eleifend mauris et risus ultrices egestas',
            'Aliquam sodales odio id eleifend tristique',
            'Urna nisl sollicitudin id varius orci quam id turpis',
            'Nulla porta lobortis ligula vel egestas',
            'Curabitur aliquam euismod dolor non ornare',
            'Sed varius a risus eget aliquam',
            'Nunc viverra elit ac laoreet suscipit',
            'Pellentesque et sapien pulvinar consectetur',
            'Ubi est barbatus nix',
            'Abnobas sunt hilotaes de placidus vita',
            'Ubi est audax amicitia',
            'Eposs sunt solems de superbus fortis',
            'Vae humani generis',
            'Diatrias tolerare tanquam noster caesium',
            'Teres talis saepe tractare de camerarius flavum sensorem',
            'Silva de secundus galatae demitto quadra',
            'Sunt accentores vitare salvus flavum parses',
            'Potus sensim ad ferox abnoba',
            'Sunt seculaes transferre talis camerarius fluctuies',
            'Era brevis ratione est',
            'Sunt torquises imitari velox mirabilis medicinaes',
            'Mineralis persuadere omnes finises desiderium',
            'Bassus fatalis classiss virtualiter transferre de flavum',
        ];
    }
}
