<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Contact;
use App\Models\Course;
use App\Models\MainSlider;
use App\Models\MediaCenter;
use App\Models\Post;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as FakerFactory;

class MassiveTablesSeeder extends Seeder
{
    public function run(): void
    {
        $count = 3;
        $faker = FakerFactory::create();
        $utcNow = Carbon::now('UTC');
        $now = $utcNow->format('Y-m-d H:i:s');
        DB::statement("SET time_zone = '+00:00'");

        Schema::disableForeignKeyConstraints();

        DB::table('personal_access_tokens')->truncate();
        DB::table('password_reset_tokens')->truncate();
        DB::table('sessions')->truncate();
        DB::table('cache')->truncate();
        DB::table('cache_locks')->truncate();
        DB::table('jobs')->truncate();
        DB::table('job_batches')->truncate();
        DB::table('failed_jobs')->truncate();

        Certificate::truncate();
        MediaCenter::truncate();
        Review::truncate();
        MainSlider::truncate();
        Post::truncate();
        Course::truncate();
        Contact::truncate();
        Setting::truncate();
        User::truncate();

        Schema::enableForeignKeyConstraints();

        $users = User::factory()->count($count)->create();
        $userIds = $users->pluck('id')->all();
        Setting::factory()->count($count)->create();

        Course::factory()->count($count)->create([
            'created_by' => fn () => $faker->randomElement($userIds),
        ]);

        Post::factory()->count($count)->create([
            'created_by' => fn () => $faker->randomElement($userIds),
        ]);

        MainSlider::factory()->count($count)->create([
            'created_by' => fn () => $faker->randomElement($userIds),
        ]);

        Review::factory()->count($count)->create([
            'approved_by' => fn () => $faker->randomElement($userIds),
        ]);

        MediaCenter::factory()->count($count)->create([
            'created_by' => fn () => $faker->randomElement($userIds),
        ]);

        Contact::factory()->count($count)->create();

        Certificate::factory()->count($count)->create([
            'created_by' => fn () => $faker->randomElement($userIds),
        ]);

          $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Mirag Academy Admin',
                'password' => Hash::make('12345678'),
            ]
        );

        DB::table('personal_access_tokens')->insert(
            collect(range(1, $count))->map(fn () => [
                'tokenable_type' => User::class,
                'tokenable_id' => $faker->randomElement($userIds),
                'name' => $faker->word(),
                'token' => hash('sha256', Str::uuid()->toString() . Str::random(40)),
                'abilities' => json_encode(['*']),
                'last_used_at' => $faker->dateTimeBetween('-3 months', 'now', 'UTC')->format('Y-m-d H:i:s'),
                'expires_at' => $faker->dateTimeBetween('now', '+6 months', 'UTC')->format('Y-m-d H:i:s'),
                'created_at' => $now,
                'updated_at' => $now,
            ])->all()
        );

        DB::table('password_reset_tokens')->insert(
            collect(range(1, $count))->map(fn ($i) => [
                'email' => "reset{$i}@example.com",
                'token' => hash('sha256', Str::random(64)),
                'created_at' => $now,
            ])->all()
        );

        DB::table('sessions')->insert(
            collect(range(1, $count))->map(fn () => [
                'id' => Str::random(40),
                'user_id' => $faker->randomElement($userIds),
                'ip_address' => $faker->ipv4(),
                'user_agent' => $faker->userAgent(),
                'payload' => base64_encode(json_encode(['demo' => true])),
                'last_activity' => $utcNow->timestamp - $faker->numberBetween(0, 86400),
            ])->all()
        );

        DB::table('cache')->insert(
            collect(range(1, $count))->map(fn ($i) => [
                'key' => "cache_key_{$i}",
                'value' => serialize($faker->sentence()),
                'expiration' => $utcNow->copy()->addDays(7)->timestamp,
            ])->all()
        );

        DB::table('cache_locks')->insert(
            collect(range(1, $count))->map(fn ($i) => [
                'key' => "cache_lock_{$i}",
                'owner' => Str::uuid()->toString(),
                'expiration' => $utcNow->copy()->addHours(2)->timestamp,
            ])->all()
        );

        DB::table('jobs')->insert(
            collect(range(1, $count))->map(fn () => [
                'queue' => $faker->randomElement(['default', 'emails', 'notifications']),
                'payload' => json_encode(['displayName' => 'DemoJob', 'job' => 'Illuminate\\Queue\\CallQueuedHandler@call']),
                'attempts' => $faker->numberBetween(0, 3),
                'reserved_at' => null,
                'available_at' => $utcNow->timestamp,
                'created_at' => $utcNow->timestamp,
            ])->all()
        );

        DB::table('job_batches')->insert(
            collect(range(1, $count))->map(fn ($i) => [
                'id' => (string) Str::uuid(),
                'name' => "batch_{$i}",
                'total_jobs' => $faker->numberBetween(1, 200),
                'pending_jobs' => $faker->numberBetween(0, 50),
                'failed_jobs' => $faker->numberBetween(0, 10),
                'failed_job_ids' => json_encode([]),
                'options' => json_encode([]),
                'cancelled_at' => null,
                'created_at' => $utcNow->timestamp,
                'finished_at' => null,
            ])->all()
        );

        DB::table('failed_jobs')->insert(
            collect(range(1, $count))->map(fn () => [
                'uuid' => (string) Str::uuid(),
                'connection' => 'database',
                'queue' => $faker->randomElement(['default', 'emails', 'notifications']),
                'payload' => json_encode(['displayName' => 'FailedDemoJob']),
                'exception' => 'Demo exception for seeding.',
                'failed_at' => $now,
            ])->all()
        );
    }
}
