Hello Admin,

A new teacher application was submitted on {{ config('app.name') }}.

APPLICATION DETAILS
-------------------
Applicant Name: {{ $application->name }}
Email: {{ $application->email }}
Phone: {{ $application->phone }}
Country: {{ $application->country }}
Job Title: {{ $application->job_title }}
Submitted At: {{ $application->created_at?->toDateTimeString() }}

Application Message:
{{ $application->description }}

Dashboard: {{ url('/admin/staff') }}
