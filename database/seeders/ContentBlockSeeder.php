<?php

namespace Database\Seeders;

use App\Models\ContentBlock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ContentBlockSeeder extends Seeder
{
    /**
     * @var array<string, string>
     */
    private array $files = [
        'site-settings' => 'site-settings.json',
        'home-content' => 'home-content.json',
        'journal-content' => 'journal-content.json',
        'journal-articles' => 'journal-articles.json',
    ];

    public function run(): void
    {
        foreach ($this->files as $key => $file) {
            $path = resource_path("data/{$file}");

            if (! File::exists($path)) {
                $this->command?->warn("Skipped missing content file: {$file}");
                continue;
            }

            $payload = json_decode(File::get($path), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException("Invalid JSON in {$file}: ".json_last_error_msg());
            }

            ContentBlock::query()->updateOrCreate(
                ['key' => $key],
                ['payload' => $payload],
            );
        }
    }
}
