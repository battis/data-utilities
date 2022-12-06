<?php

namespace Battis\DataUtilities;

use Battis\Hydratable\Hydrate;

class Text
{
    /**
     * Converts $title to Title Case, and returns the result
     *
     * @param string $title
     * @param array $params An associative array of additional special cases,
     *                      e.g. (with any subset of the keys below)
     *                      ```
     * [
     *     'lowerCaseWords' => ['foo', 'bar'],
     *     'allCapsWords' => ['BAZ'],
     *     'camelCaseWords' => ['foobarbaz' => 'fooBarBaz'],
     *     'spaceEquivalents' => ['\t']
     * ]
     * ```
     *
     * @return string
     *
     * @see http://www.sitepoint.com/title-case-in-php/ SitePoint
     **/
    public static function titleCase($title, $params = [])
    {
        $hydrate = new Hydrate();
        /*
         * Our array of 'small words' which shouldn't be capitalized if they
         * aren't the first word.  Add your own words to taste.
         */
        $lowerCaseWords = $hydrate($params['lowerCaseWords'] ?? [], [
            'of','a','the','and','an','or','nor','but','is','if','then','else',
            'when','at','from','by','on','off','for','in','out','over','to',
            'into','with'
        ]);

        $allCapsWords = $hydrate($params['allCapsWords'] ?? [], [
            'i', 'ii', 'iii', 'iv', 'v', 'vi', 'sis', 'csv', 'php', 'html',
            'lti'
        ]);

        $camelCaseWords = $hydrate($params['camelCaseWords'] ?? [], [
            'github' => 'GitHub'
        ]);

        $spaceEquivalents = $hydrate($params['spaceEquivalents'] ?? [], [
            '\s', '_'
        ]);

        /* add a space after each piece of punctuation */
        $title = preg_replace('/([^a-z0-9])\s*/i', '$1 ', strtolower($title));

        // TODO smart em- and en-dashes
        // TODO superscripts for 1st, 2nd, 3rd

        /* Split the string into separate words */
        $words = preg_split('/[' . implode('', $spaceEquivalents) . ']+/', $title, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($words as $key => $word) {
            if (in_array($word, $allCapsWords)) {
                $words[$key] = strtoupper($word);
            } elseif (array_key_exists($word, $camelCaseWords)) {
                $words[$key] = $camelCaseWords[$word];
            } elseif ($key == 0 || !in_array($word, $lowerCaseWords)) {
                $words[$key] = ucwords($word);
            }
        }

        /* Join the words back into a string */
        $newtitle = implode(' ', $words);

        return $newtitle;
    }

    /**
     * What portion of string `$a` and `$b` overlap with each other?
     *
     * For example if `$a` is `'abcdefg'` and `$b` is `'fgjkli'`, the overlapping
     * portion would be `'fg'`.
     *
     * @param string $a
     * @param string $b
     * @param boolean $swap Attempt to swap `$a` and `$b` to find overlap. (default: `true`)
     * @return string Overlapping portion of `$a` and `$b`, `''` if no overlap
     */
    public static function overlap(string $a, string $b, $swap = true)
    {
        for ($i = 0; $i < strlen($a); $i++) {
            $overlap = true;
            for ($j = 0; $j < strlen($b) && $i + $j < strlen($a); $j++) {
                if ($a[$i+$j] !== $b[$j]) {
                    $overlap = false;
                    break;
                }
            }
            if ($overlap) {
                return substr($a, $i, $j);
            }
        }
        if ($swap) {
            return static::overlap($b, $a, false);
        }
        return '';
    }

    /**
     * Convert a string from snake_case to PascalCase
     * @param string $snake_case original string (presumed to be in snake_case)
     * @return string PascalCase version of the string
     */
    public static function snake_case_to_PascalCase(string $snake_case): string
    {
        return join(
            array_map(
                fn($token) => strtoupper(substr($token, 0, 1)) .
                    substr($token, 1),
                explode("_", $snake_case)
            )
        );
    }

    /**
     * Convert a string from camelCase to snake_case
     *
     * Word breaks are assumed to be indicated by changes in case, so
     * `fooBarBaz` will become `foo_bar_baz`, but `fooBARBaz` will _also_
     * become `foo_bar_baz`
     *
     * @param string $camelCase original string (presumed to be in
     *                          camelCase -- or PascalCase)
     * @return string
     */
    public static function camelCase_to_snake_case(string $camelCase): string
    {
        $snake_case = $camelCase;
        foreach (
            [
                "/([^0-9])([0-9])/", // separate numeric phrases
                "/([A-Z])([A-Z][a-z])/", // separate trailing word from acronym
                "/([^A-Z])([A-Z])/", // separate end of word from trailing word,
                "/([^_])_+([^_])/", // minimize underscores
            ]
            as $regexp
        ) {
            $snake_case = preg_replace($regexp, "$1_$2", $snake_case);
        }
        return strtolower($snake_case);
    }

    /**
     * Is this a vowel?
     *
     * The string is processed case-insensitively, and by default `y` is a
     * vowel. To exclude `y` pass `false` as `$sometimesY` argument.
     * Multi-character strings will never be vowels.
     *
     * @param string $char the string to check (ideally a single character)
     * @param bool $sometimesY (Optional, default is `TRUE`)
     * @return bool
     */
    public static function isVowel(string $char, bool $sometimesY = true): bool
    {
        switch (strtolower($char)) {
            case 'a':
            case 'e':
            case 'i':
            case 'o':
            case 'u':
                return true;
            case 'y':
                return $sometimesY;
            default:
                return false;
        }
    }

    /**
     * Pluralize a singular word
     *
     * This uses assumptions based on the English language and its
     * pluralization "rules" (e.g. words end in `ch`, `s`, `sh`, `x`, and
     * `z` will be pluralized by adding `es` rather than `s` and words
     * ending in a consonant + `y` will be pluralized by changing the `y`
     * to `ies`, as in `lazy` becomes `lazies`, while `day` should become
     * `days`). The pluralization matches the last character (or character
     * group) that is used to determine the pluralizing suffix (so `fisH`
     * would become `fisHES` while `FISh` would become `FIShes`).
     *
     * @param string $singular
     * @return string
     */
    public static function pluralize(string $singular): string
    {
        switch (substr($singular, -1)) {
            case "s":
            case "x":
            case "z":
                return "{$singular}es";
            case "S":
            case "X":
            case "Z":
                return "{$singular}ES"; // TODO dry out this code somehow
            case "y":
                if (self::isVowel(substr($singular, -2, 1))) {
                    return "{$singular}s";
                }
                return substr($singular, 0, strlen($singular) - 1) . "ies";
            case "Y":
                if (self::isVowel(substr($singular, -2, 1))) {
                    return "{$singular}S";
                }
                return substr($singular, 0, strlen($singular) - 1) . "IES";
            default:
                switch (substr($singular, -2)) {
                    case "sh":
                    case "Sh":
                    case "ch":
                    case "Ch":
                        return "{$singular}es";
                    case "SH":
                    case "sH":
                    case "CH":
                    case "cH":
                        return "{$singular}ES";
                    default:
                        if (
                            substr($singular, -1) ===
                            strtolower(substr($singular, -1))
                        ) {
                            return "{$singular}s";
                        } else {
                            return "{$singular}S";
                        }
                }
        }
    }
}
