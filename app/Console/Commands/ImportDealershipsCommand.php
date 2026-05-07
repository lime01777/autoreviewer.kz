<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Dealership;
use App\Services\DealershipNormalizer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;
use Illuminate\Support\Str;

class ImportDealershipsCommand extends Command
{
    protected $signature = 'dealerships:import {file}';
    protected $description = 'Import dealerships from a CSV file';

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $csv = Reader::createFromPath($filePath);
        $csv->setHeaderOffset(0);
        $csv->setDelimiter(',');

        $stats = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        $importLog = Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/dealerships_import.log'),
        ]);

        $this->info('Starting import...');
        
        // Convert CSV iterator to array to get count
        $records = iterator_to_array($csv);
        $bar = $this->output->createProgressBar(count($records));
        $bar->start();

        foreach ($records as $index => $row) {
            /** @var array<string, mixed> $row */
            $row = (array) $row;
            $validator = Validator::make($row, [
                'title' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'source_url' => 'required|url',
                'email' => 'nullable|email',
                'website' => 'nullable|url',
                'phone' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $errorMsg = "Row " . ($index + 2) . " failed validation: " . implode(', ', $validator->errors()->all());
                $stats['errors'][] = $errorMsg;
                $importLog->error($errorMsg, ['row' => $row]);
                $stats['skipped']++;
                $bar->advance();
                continue;
            }

            try {
                // ── Apply normalization ────────────────────────────────────
                $normalizer = app(DealershipNormalizer::class);

                [$city, , $cityUnknown] = $normalizer->normalizeCity($row['city'] ?? '');
                [$phone, , $phoneInvalid] = $normalizer->normalizePhone($row['phone'] ?? null);
                [$whatsapp, ,] = $normalizer->normalizePhone($row['whatsapp'] ?? null);
                [$website, ,] = $normalizer->normalizeUrl($row['website'] ?? null);
                [$sourceUrl, ,] = $normalizer->normalizeUrl($row['source_url'] ?? null);
                $brands = $normalizer->normalizeBrands($row['brands'] ?? '');

                // Auto-flag suspicious rows
                $dataStatus = $row['data_status'] ?? 'draft';
                if (($cityUnknown || $phoneInvalid) && $dataStatus === 'draft') {
                    $dataStatus = 'needs_review';
                }

                // Slug: use provided or generate; ensure uniqueness
                static $usedSlugs = [];
                $slug = !empty($row['slug']) && !Dealership::where('slug', $row['slug'])->exists()
                    ? $row['slug']
                    : $normalizer->normalizeSlug(
                        $row['title'] ?? '',
                        $city,
                        $row['brand'] ?? '',
                        $usedSlugs
                    );
                if (!in_array($slug, $usedSlugs)) {
                    $usedSlugs[] = $slug;
                }

                // Helper function to convert empty strings to null
                $toNull = fn($value) => (trim($value ?? '') === '') ? null : $value;

                $data = [
                    'title'            => $row['title'],
                    'legal_name'       => $toNull($row['legal_name'] ?? null),
                    'short_description'=> $toNull($row['short_description']) ?? ($row['title'] . ' в ' . $city),
                    'full_description' => $toNull($row['full_description']) ?? ($row['title'] . ' — профессиональный автосалон в городе ' . $city),
                    'type'             => $row['type'] ?? 'dealership',
                    'brand'            => $toNull($row['brand'] ?? null),
                    'brands'           => $brands,
                    'is_official_dealer'=> filter_var($row['is_official_dealer'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'country'          => $row['country'] ?? 'Kazakhstan',
                    'city'             => $city,
                    'district'         => $toNull($row['district'] ?? null),
                    'address'          => $toNull($row['address'] ?? null),
                    'phone'            => $phone,
                    'whatsapp'         => $whatsapp,
                    'email'            => $toNull($row['email'] ?? null),
                    'website'          => $website,
                    'instagram'        => $toNull($row['instagram'] ?? null),
                    'working_hours'    => $toNull($row['working_hours'] ?? null),
                    'latitude'         => $toNull($row['latitude'] ?? null),
                    'longitude'        => $toNull($row['longitude'] ?? null),
                    'source_name'      => $toNull($row['source_name']) ?? 'CSV Import',
                    'source_url'       => $sourceUrl ?? $row['source_url'],
                    'source_checked_at'=> $toNull($row['source_checked_at']) ?? now(),
                    'data_verified'    => filter_var($row['data_verified'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'data_status'      => $dataStatus,
                    'status'           => 'published',
                ];

                $dealership = Dealership::where('slug', $slug)->first();

                if ($dealership) {
                    $dealership->update($data);
                    $stats['updated']++;
                } else {
                    Dealership::create(array_merge($data, ['slug' => $slug]));
                    $stats['created']++;
                }
            } catch (\Exception $e) {
                $errorMsg = "Row " . ($index + 2) . " failed to import: " . $e->getMessage();
                $stats['errors'][] = $errorMsg;
                $importLog->error($errorMsg, ['row' => $row, 'exception' => $e]);
                $stats['skipped']++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Metric', 'Count'],
            [
                ['Created', $stats['created']],
                ['Updated', $stats['updated']],
                ['Skipped', $stats['skipped']],
            ]
        );

        if (!empty($stats['errors'])) {
            $this->error('Errors encountered:');
            foreach (array_slice($stats['errors'], 0, 10) as $error) {
                $this->line("- {$error}");
            }
            if (count($stats['errors']) > 10) {
                $this->line("... and " . (count($stats['errors']) - 10) . " more. Check storage/logs/dealerships_import.log");
            }
        }

        $this->info('Import completed!');
    }
}
