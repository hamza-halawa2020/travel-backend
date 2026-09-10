Hello Admin,

A new student review was submitted on {{ config('app.name') }}.

REVIEW DETAILS
--------------
Reviewer Name: {{ $review->name }}
Current Status: {{ $review->status ? 'Approved' : 'Pending approval' }}
Submitted At: {{ $review->created_at?->toDateTimeString() }}

Review Text:
{{ $review->review }}

Dashboard: {{ url('/admin/reviews') }}
