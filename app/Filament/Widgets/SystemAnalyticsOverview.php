<?php

namespace App\Filament\Widgets;

use App\Models\Certificate;
use App\Models\Contact;
use App\Models\Course;
use App\Models\MainSlider;
use App\Models\MediaCenter;
use App\Models\Post;
use App\Models\CourseCategory;
use App\Models\Staff;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class SystemAnalyticsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'System Analytics';

    protected function getStats(): array
    {
        $fromDate = $this->pageFilters['from_date'] ?? null;
        $toDate = $this->pageFilters['to_date'] ?? null;

        return [
            Stat::make('Users', $this->countByDateRange(User::query(), $fromDate, $toDate))->color('primary'),
            Stat::make('Contacts', $this->countByDateRange(Contact::query(), $fromDate, $toDate))->color('warning'),
            // Stat::make('Posts', $this->countByDateRange(Post::query(), $fromDate, $toDate))->color('success'),
            Stat::make('Courses', $this->countByDateRange(Course::query(), $fromDate, $toDate))->color('info'),
            Stat::make('Reviews', $this->countByDateRange(Review::query(), $fromDate, $toDate))->color('gray'),
            Stat::make('staffs', $this->countByDateRange(Staff::query(), $fromDate, $toDate))->color('gray'),
            Stat::make('Categories', $this->countByDateRange(CourseCategory::query(), $fromDate, $toDate))->color('gray'),
            // Stat::make('Certificates', $this->countByDateRange(Certificate::query(), $fromDate, $toDate))->color('danger'),
            // Stat::make('Media Center', $this->countByDateRange(MediaCenter::query(), $fromDate, $toDate))->color('success'),
            // Stat::make('Main Sliders', $this->countByDateRange(MainSlider::query(), $fromDate, $toDate))->color('primary'),
        ];
    }

    private function countByDateRange(Builder $query, ?string $fromDate, ?string $toDate): int
    {
        $from = $fromDate ? Carbon::parse($fromDate)->startOfDay() : null;
        $to = $toDate ? Carbon::parse($toDate)->endOfDay() : null;

        if ($from && $to && $from->greaterThan($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        if ($from) {
            $query->where('created_at', '>=', $from);
        }

        if ($to) {
            $query->where('created_at', '<=', $to);
        }

        return $query->count();
    }
}
