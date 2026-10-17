<?php

declare(strict_types=1);

namespace MicroweberPackages\Filament\Support;

final class AdminFixtureGuard
{
    /**
     * Name-pattern blocklist. Regexes are case-insensitive where the
     * pattern flag does the lifting (anchored variants are explicit).
     * Add a new pattern HERE rather than inline in a resource — single
     * source of truth.
     */
    public const FIXTURE_LEAK_PATTERNS = [
        '/^menu test [0-9a-f]+$/i',              // PHPUnit unique-name menu fixture
        '/^test menu$/i',                         // generic scenario label
        '/^test page$/i',                         // page-side scenario label
        '/^test category$/i',                     // category-side scenario label
        '/^created via module api menu$/i',       // module-API integration test fixture
        '/^lorem\b/i',                            // lorem-ipsum cruft prefix
        '/^after$/i',                             // before/after test-step labels
        '/^before$/i',
        '/^\d+$/',                                // pure-numeric "titles" (123, 456) from quick-test setup
        '/^DuskTest/i',                            // Dusk browser-test fixture (DuskTestPage, DuskTestPost, DuskTestProduct)
        '/^Test post$/i',                          // generic Dusk/PHPUnit post fixture
        '/^Active Menu$/i',                                '/^Disabled Menu$/i',                              '/^my_new_menu$/i',                                '/^Richest people in the world$/i',            ];

        public const FAKER_LOREM_WORDS = [
        'accusamus', 'accusantium', 'adipisci', 'alias', 'aliquam', 'aliquid',
        'amet', 'animi', 'aperiam', 'architecto', 'asperiores', 'aspernatur',
        'assumenda', 'atque', 'autem', 'beatae', 'blanditiis', 'commodi',
        'consequatur', 'consectetur', 'consequuntur', 'corporis', 'corrupti',
        'culpa', 'cumque', 'cupiditate', 'debitis', 'deleniti', 'deserunt',
        'dicta', 'dignissimos', 'distinctio', 'dolor', 'dolore', 'dolorem',
        'doloremque', 'dolores', 'doloribus', 'dolorum', 'ducimus', 'eaque',
        'earum', 'eius', 'eligendi', 'enim', 'esse', 'eveniet', 'excepturi',
        'exercitationem', 'expedita', 'explicabo', 'facere', 'facilis',
        'fuga', 'fugiat', 'fugit', 'harum', 'illo', 'illum', 'impedit',
        'incidunt', 'inventore', 'ipsa', 'ipsam', 'ipsum', 'iste', 'itaque',
        'iure', 'iusto', 'labore', 'laboriosam', 'laborum', 'laudantium',
        'libero', 'magnam', 'magni', 'maiores', 'maxime', 'minima', 'minus',
        'mollitia', 'molestiae', 'natus', 'necessitatibus', 'nemo', 'neque',
        'nesciunt', 'nihil', 'nisi', 'nobis', 'nostrum', 'nulla', 'numquam',
        'obcaecati', 'odio', 'odit', 'officia', 'officiis', 'omnis', 'optio',
        'pariatur', 'perferendis', 'perspiciatis', 'placeat', 'porro',
        'possimus', 'praesentium', 'provident', 'quae', 'quam', 'quas',
        'quasi', 'quia', 'quibusdam', 'quidem', 'quisquam', 'quod',
        'quos', 'ratione', 'recusandae', 'reiciendis', 'repellat',
        'repellendus', 'reprehenderit', 'repudiandae', 'rerum', 'saepe',
        'sapiente', 'sequi', 'similique', 'sint', 'soluta', 'sunt',
        'suscipit', 'tempora', 'tempore', 'temporibus', 'tenetur', 'totam',
        'ullam', 'unde', 'velit', 'veniam', 'veritatis', 'vero', 'vitae',
        'voluptas', 'voluptate', 'voluptatem', 'voluptates', 'voluptatibus',
        'voluptatum',
    ];

    /**
     * Decide whether an admin form should render a row whose label
     * is `$title`. Returns true to keep the row, false to skip it.
     *
     * Pure function: no DB calls, no Laravel boot required. Safe to
     * call inside foreach loops.
     *
     * Filter order:
     *   1. empty/whitespace → false
     *   2. FIXTURE_LEAK_PATTERNS regex match → false
     *   3. looksLikeFakerLorem (every ≥4-char word in lorem set) → false
     *   4. otherwise → true
     */
    public static function shouldRenderItem(?string $title): bool
    {
        $trimmed = trim((string) $title);
        if ($trimmed === '') {
            return false;
        }
        foreach (self::FIXTURE_LEAK_PATTERNS as $pattern) {
            if (preg_match($pattern, $trimmed) === 1) {
                return false;
            }
        }
        if (self::looksLikeFakerLorem($trimmed)) {
            return false;
        }
        return true;
    }

        public const SHORT_LATIN_WORDS = [
        'a', 'ab', 'ad', 'at', 'aut', 'cum', 'de', 'est', 'et', 'eum',
        'ex', 'id', 'in', 'non', 'quo', 'rem', 'sed', 'sit', 'sub', 'ut',
    ];

    /**
     * Detect Faker lorem-ipsum-style multi-word titles. Returns true when
     * every word is recognisable Latin (either in FAKER_LOREM_WORDS or
     * SHORT_LATIN_WORDS) and at least one word is in the long-form set.
     */
    public static function looksLikeFakerLorem(string $title): bool
    {
        $words = preg_split('/[\s\-_,.\/]+/', strtolower(trim($title))) ?: [];
        $alphaWords = array_values(array_filter(
            $words,
            fn ($w) => mb_strlen($w) >= 1 && preg_match('/^[a-z]+$/u', $w) === 1
        ));
        if (count($alphaWords) < 2) {
            return false;
        }
        $longSet = array_flip(self::FAKER_LOREM_WORDS);
        $shortSet = array_flip(self::SHORT_LATIN_WORDS);
        $longWordCount = 0;
        foreach ($alphaWords as $word) {
            if (isset($longSet[$word])) {
                $longWordCount++;
            } elseif (! isset($shortSet[$word])) {
                return false;
            }
        }
        // "Multi-word signature" requires at least TWO long-form Faker
        // words. Short Latin filler words (≤3 chars, e.g. 'et', 'in')
        // are treated as neutral — they don't disqualify, but a title
        // with only ONE long-form word + filler (e.g. "Asperiores Et")
        // does NOT match the multi-word signature.
        return $longWordCount >= 2;
    }

    /**
     * Convenience: filter an array of associative arrays by their
     * title field. Each element must carry a string-keyed `title`
     * key (the standard shape from `get_menus()`, `get_categories()`,
     * `get_content()` etc.). Returns a re-indexed array of survivors.
     */
    public static function filterByTitle(array $rows, string $titleKey = 'title'): array
    {
        return array_values(array_filter(
            $rows,
            fn ($row) => is_array($row) && self::shouldRenderItem($row[$titleKey] ?? null)
        ));
    }
}
