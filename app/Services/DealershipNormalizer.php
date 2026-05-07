<?php

namespace App\Services;

use Illuminate\Support\Str;

class DealershipNormalizer
{
    // Canonical city names (normalized form)
    private const CITY_MAP = [
        // Алматы
        'алматы'         => 'Алматы',
        'almaty'         => 'Алматы',
        'алма-ата'       => 'Алматы',
        'alma-ata'       => 'Алматы',
        'алмаата'        => 'Алматы',
        // Астана
        'астана'         => 'Астана',
        'astana'         => 'Астана',
        'нур-султан'     => 'Астана',
        'nur-sultan'     => 'Астана',
        'нурсултан'      => 'Астана',
        'nur sultan'     => 'Астана',
        // Шымкент
        'шымкент'        => 'Шымкент',
        'shymkent'       => 'Шымкент',
        'чимкент'        => 'Шымкент',
        'shimkent'       => 'Шымкент',
        // Костанай
        'костанай'       => 'Костанай',
        'kostanay'       => 'Костанай',
        'кустанай'       => 'Костанай',
        'kustanay'       => 'Костанай',
        // Караганда
        'караганда'      => 'Караганда',
        'karaganda'      => 'Караганда',
        'qaraghandy'     => 'Караганда',
        // Актобе
        'актобе'         => 'Актобе',
        'aktobe'         => 'Актобе',
        'актюбинск'      => 'Актобе',
        'aktyubinsk'     => 'Актобе',
        // Павлодар
        'павлодар'       => 'Павлодар',
        'pavlodar'       => 'Павлодар',
        // Атырау
        'атырау'         => 'Атырау',
        'atyrau'         => 'Атырау',
        'гурьев'         => 'Атырау',
        'guryev'         => 'Атырау',
        // Актау
        'актау'          => 'Актау',
        'aktau'          => 'Актау',
        'шевченко'       => 'Актау',
        'shevchenko'     => 'Актау',
        // Усть-Каменогорск
        'усть-каменогорск'  => 'Усть-Каменогорск',
        'ust-kamenogorsk'   => 'Усть-Каменогорск',
        'усть каменогорск'  => 'Усть-Каменогорск',
        'öskemen'           => 'Усть-Каменогорск',
        'оскемен'           => 'Усть-Каменогорск',
        // Кокшетау
        'кокшетау'       => 'Кокшетау',
        'kokshetau'      => 'Кокшетау',
        'кокчетав'       => 'Кокшетау',
        'kokchetav'      => 'Кокшетау',
        // Тараз
        'тараз'          => 'Тараз',
        'taraz'          => 'Тараз',
        'джамбул'        => 'Тараз',
        'zhambyl'        => 'Тараз',
        // Кызылорда
        'кызылорда'      => 'Кызылорда',
        'kyzylorda'      => 'Кызылорда',
        'кзыл-орда'      => 'Кызылорда',
        // Уральск
        'уральск'        => 'Уральск',
        'uralsk'         => 'Уральск',
        'орал'           => 'Уральск',
        'oral'           => 'Уральск',
        // Петропавловск
        'петропавловск'  => 'Петропавловск',
        'petropavlovsk'  => 'Петропавловск',
        'петропавл'      => 'Петропавловск',
        'petropavl'      => 'Петропавловск',
    ];

    /**
     * Normalize a city name to canonical Russian form.
     * Returns [normalized_city, was_changed, needs_review]
     */
    public function normalizeCity(string $city): array
    {
        $key = mb_strtolower(trim($city));
        if (isset(self::CITY_MAP[$key])) {
            return [self::CITY_MAP[$key], $city !== self::CITY_MAP[$key], false];
        }
        // Unknown city — keep as-is, flag for review
        return [trim($city), false, true];
    }

    /**
     * Normalize a Kazakhstan phone number to +7 XXX XXX XX XX format.
     * Returns [normalized_phone, was_changed, is_invalid]
     */
    public function normalizePhone(?string $phone): array
    {
        if (empty($phone)) {
            return [null, false, false];
        }

        $original = $phone;
        // Strip everything except digits and leading +
        $digits = preg_replace('/[^\d]/', '', $phone);

        // Handle 8-XXX..., +7-XXX..., 7XXX..., handle 10-digit (without country code)
        if (strlen($digits) === 11 && str_starts_with($digits, '7')) {
            // Correct: 7XXXXXXXXXX
            $formatted = '+7 ' . substr($digits, 1, 3) . ' ' . substr($digits, 4, 3) . ' ' . substr($digits, 7, 2) . ' ' . substr($digits, 9, 2);
            return [$formatted, $original !== $formatted, false];
        }

        if (strlen($digits) === 11 && str_starts_with($digits, '8')) {
            // Soviet-style: 8XXXXXXXXXX → +7
            $formatted = '+7 ' . substr($digits, 1, 3) . ' ' . substr($digits, 4, 3) . ' ' . substr($digits, 7, 2) . ' ' . substr($digits, 9, 2);
            return [$formatted, true, false];
        }

        if (strlen($digits) === 10) {
            // No country code
            $formatted = '+7 ' . substr($digits, 0, 3) . ' ' . substr($digits, 3, 3) . ' ' . substr($digits, 6, 2) . ' ' . substr($digits, 8, 2);
            return [$formatted, true, false];
        }

        // Invalid — keep original, mark for review
        return [$original, false, true];
    }

    /**
     * Normalize a website URL to include https://.
     * Returns [normalized_url, was_changed, is_invalid]
     */
    public function normalizeUrl(?string $url): array
    {
        if (empty($url)) {
            return [null, false, false];
        }

        $original = trim($url);

        // Remove common typos
        $url = trim($url, " \t\n\r/");

        // If already has scheme
        if (preg_match('/^https?:\/\//i', $url)) {
            $isValid = filter_var($url, FILTER_VALIDATE_URL) !== false;
            return [$url, $url !== $original, !$isValid];
        }

        // Add https://
        $url = 'https://' . $url;
        $isValid = filter_var($url, FILTER_VALIDATE_URL) !== false;

        return [$url, true, !$isValid];
    }

    /**
     * Generate a unique slug from title and optional city/brand.
     * Pass $existingSlugs as a reference to track used slugs in batch.
     */
    public function normalizeSlug(string $title, string $city = '', string $brand = '', array &$existingSlugs = []): string
    {
        $base = Str::slug($title);

        if (!in_array($base, $existingSlugs)) {
            $existingSlugs[] = $base;
            return $base;
        }

        // Try adding city
        if ($city) {
            $withCity = Str::slug($title . ' ' . $city);
            if (!in_array($withCity, $existingSlugs)) {
                $existingSlugs[] = $withCity;
                return $withCity;
            }
        }

        // Try adding brand
        if ($brand) {
            $withBrand = Str::slug($title . ' ' . $brand);
            if (!in_array($withBrand, $existingSlugs)) {
                $existingSlugs[] = $withBrand;
                return $withBrand;
            }
        }

        // Try adding city + brand
        if ($city && $brand) {
            $withBoth = Str::slug($title . ' ' . $city . ' ' . $brand);
            if (!in_array($withBoth, $existingSlugs)) {
                $existingSlugs[] = $withBoth;
                return $withBoth;
            }
        }

        // Append counter
        $i = 2;
        while (in_array($base . '-' . $i, $existingSlugs)) {
            $i++;
        }
        $slug = $base . '-' . $i;
        $existingSlugs[] = $slug;
        return $slug;
    }

    /**
     * Normalize brands field: string or JSON → array of strings.
     */
    public function normalizeBrands(mixed $brands): array
    {
        if (empty($brands)) {
            return [];
        }
        if (is_array($brands)) {
            return array_map('trim', $brands);
        }
        // Try JSON first
        $decoded = json_decode($brands, true);
        if (is_array($decoded)) {
            return array_map('trim', $decoded);
        }
        // Comma-separated string
        return array_filter(array_map('trim', explode(',', $brands)));
    }

    /**
     * Detect potential duplicate keys from a row.
     */
    public function getDuplicateKeys(array $row): array
    {
        $keys = [];

        if (!empty($row['website'])) {
            $keys['website'] = $this->normalizeUrl($row['website'])[0];
        }

        if (!empty($row['phone'])) {
            $keys['phone'] = preg_replace('/[^\d]/', '', $row['phone']);
        }

        if (!empty($row['title']) && !empty($row['city'])) {
            $keys['title_city'] = mb_strtolower(trim($row['title'])) . '|' . mb_strtolower(trim($row['city']));
        }

        if (!empty($row['address']) && !empty($row['city'])) {
            $keys['address_city'] = mb_strtolower(trim($row['address'])) . '|' . mb_strtolower(trim($row['city']));
        }

        return $keys;
    }
}
