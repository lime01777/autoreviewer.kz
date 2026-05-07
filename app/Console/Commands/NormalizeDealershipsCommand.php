<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DealershipNormalizer;
use League\Csv\Reader;
use League\Csv\Writer;

class NormalizeDealershipsCommand extends Command
{
    protected $signature = 'dealerships:normalize {file} {--output=} {--dry-run}';
    protected $description = 'Normalize CSV file with dealerships before import: cities, phones, URLs, slugs, and detect duplicates.';

    public function handle(DealershipNormalizer $normalizer)
    {
        $filePath = $this->argument('file');
        $outputPath = $this->option('output') ?? str_replace('.csv', '_normalized.csv', $filePath);
        $duplicatesReportPath = storage_path('app/imports/dealerships_duplicates_report.csv');
        $isDryRun = $this->option('dry-run');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);

        $headers = $csv->getHeader();
        $records = iterator_to_array($csv);

        $this->info('📂 Reading: ' . $filePath);
        $this->info('📊 Total rows: ' . count($records));
        $this->newLine();

        // Stats
        $stats = [
            'total'               => count($records),
            'cities_normalized'   => 0,
            'cities_unknown'      => 0,
            'phones_normalized'   => 0,
            'phones_invalid'      => 0,
            'urls_normalized'     => 0,
            'urls_invalid'        => 0,
            'slugs_generated'     => 0,
            'needs_review_added'  => 0,
            'duplicates_detected' => 0,
        ];

        $normalizedRows = [];
        $seenSlugs      = [];
        $seenKeys       = [
            'website'      => [],
            'phone'        => [],
            'title_city'   => [],
            'address_city' => [],
        ];
        $duplicates = [];

        $bar = $this->output->createProgressBar(count($records));
        $bar->start();

        foreach ($records as $index => $row) {
            $rowNum = $index + 2; // 1-indexed + header
            $needsReview = false;
            $dupReasons  = [];

            // ── 1. Normalize city ──────────────────────────────────────────
            [$city, $cityChanged, $cityUnknown] = $normalizer->normalizeCity($row['city'] ?? '');
            $row['city'] = $city;
            if ($cityChanged) $stats['cities_normalized']++;
            if ($cityUnknown) {
                $stats['cities_unknown']++;
                $needsReview = true;
            }

            // ── 2. Normalize phone ─────────────────────────────────────────
            [$phone, $phoneChanged, $phoneInvalid] = $normalizer->normalizePhone($row['phone'] ?? null);
            $row['phone'] = $phone;
            if ($phoneChanged) $stats['phones_normalized']++;
            if ($phoneInvalid) {
                $stats['phones_invalid']++;
                $needsReview = true;
            }

            // Normalize whatsapp the same way
            [$wa, , $waInvalid] = $normalizer->normalizePhone($row['whatsapp'] ?? null);
            $row['whatsapp'] = $wa;
            if ($waInvalid) $needsReview = true;

            // ── 3. Normalize website ───────────────────────────────────────
            [$website, $urlChanged, $urlInvalid] = $normalizer->normalizeUrl($row['website'] ?? null);
            $row['website'] = $website;
            if ($urlChanged) $stats['urls_normalized']++;
            if ($urlInvalid) {
                $stats['urls_invalid']++;
                $needsReview = true;
            }

            // Normalize source_url the same way
            [$sourceUrl, ,] = $normalizer->normalizeUrl($row['source_url'] ?? null);
            $row['source_url'] = $sourceUrl;

            // ── 4. Normalize brands ────────────────────────────────────────
            $brands = $normalizer->normalizeBrands($row['brands'] ?? '');
            $row['brands'] = implode(', ', $brands); // Keep as string in CSV

            // ── 5. Generate / validate slug ────────────────────────────────
            $slug = !empty($row['slug'])
                ? $row['slug']
                : $normalizer->normalizeSlug(
                    $row['title'] ?? '',
                    $row['city'] ?? '',
                    $row['brand'] ?? '',
                    $seenSlugs
                );

            // Ensure slug uniqueness even if pre-supplied
            if (in_array($slug, $seenSlugs)) {
                $slug = $normalizer->normalizeSlug(
                    $row['title'] ?? '',
                    $row['city'] ?? '',
                    $row['brand'] ?? '',
                    $seenSlugs
                );
                $stats['slugs_generated']++;
            } else {
                $seenSlugs[] = $slug;
            }
            $row['slug'] = $slug;

            // ── 6. Duplicate detection ─────────────────────────────────────
            $dupKeys = $normalizer->getDuplicateKeys($row);

            foreach ($dupKeys as $type => $value) {
                if (!empty($value) && in_array($value, $seenKeys[$type])) {
                    $dupReasons[] = "Duplicate {$type}: {$value}";
                    $stats['duplicates_detected']++;
                } else {
                    $seenKeys[$type][] = $value;
                }
            }

            if (!empty($dupReasons)) {
                $needsReview = true;
                $duplicates[] = [
                    'row'        => $rowNum,
                    'title'      => $row['title'] ?? '',
                    'city'       => $row['city'] ?? '',
                    'slug'       => $slug,
                    'reasons'    => implode(' | ', $dupReasons),
                    'phone'      => $row['phone'] ?? '',
                    'website'    => $row['website'] ?? '',
                    'address'    => $row['address'] ?? '',
                    'source_url' => $row['source_url'] ?? '',
                ];
            }

            // ── 7. Set data_status ─────────────────────────────────────────
            if ($needsReview && in_array($row['data_status'] ?? '', ['', 'draft'])) {
                $row['data_status'] = 'needs_review';
                $stats['needs_review_added']++;
            }

            $normalizedRows[] = $row;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // ── Print stats ────────────────────────────────────────────────────
        $this->table(['Metric', 'Count'], [
            ['Total rows processed',        $stats['total']],
            ['Cities normalized',           $stats['cities_normalized']],
            ['Unknown cities (needs_review)',$stats['cities_unknown']],
            ['Phones normalized',           $stats['phones_normalized']],
            ['Invalid phones (needs_review)',$stats['phones_invalid']],
            ['URLs normalized',             $stats['urls_normalized']],
            ['Invalid URLs (needs_review)', $stats['urls_invalid']],
            ['Slugs auto-generated',        $stats['slugs_generated']],
            ['Duplicates detected',         $stats['duplicates_detected']],
            ['Rows marked needs_review',    $stats['needs_review_added']],
        ]);

        if ($isDryRun) {
            $this->warn('--dry-run mode: no files written.');
            return 0;
        }

        // ── Write normalized CSV ───────────────────────────────────────────
        $writer = Writer::createFromPath($outputPath, 'w+');
        $writer->setDelimiter(',');
        $writer->insertOne($headers);
        $writer->insertAll($normalizedRows);
        $this->info("✅ Normalized CSV saved: {$outputPath}");

        // ── Write duplicates report ────────────────────────────────────────
        if (!empty($duplicates)) {
            $dupWriter = Writer::createFromPath($duplicatesReportPath, 'w+');
            $dupWriter->setDelimiter(',');
            $dupWriter->insertOne(['row', 'title', 'city', 'slug', 'reasons', 'phone', 'website', 'address', 'source_url']);
            $dupWriter->insertAll($duplicates);
            $this->warn("⚠️  Duplicates report saved: {$duplicatesReportPath}");
            $this->warn("   {$stats['duplicates_detected']} duplicate group(s) found. Review before importing.");
        } else {
            $this->info('✅ No duplicates detected.');
        }

        $this->newLine();
        $this->info('Next step: php artisan dealerships:import ' . $outputPath);

        return 0;
    }
}
