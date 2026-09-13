Hello Admin,

You received a new travel enquiry from {{ config('app.name') }}.

ENQUIRY DETAILS
---------------
Name: {{ $contact->name }}
Phone / WhatsApp: {{ $contact->phone }}
Email: {{ $contact->email ?? '-' }}
Service: {{ $contact->service ?? '-' }}
Field A: {{ $contact->field_a ?? '-' }}
Field B: {{ $contact->field_b ?? '-' }}
Submitted At: {{ $contact->created_at?->toDateTimeString() }}

Details:
{{ $contact->details ?? '-' }}

Dashboard: {{ url('/admin/enquiries') }}
