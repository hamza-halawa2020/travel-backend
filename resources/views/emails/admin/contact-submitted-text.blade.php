Hello Admin,

You received a new travel enquiry from {{ config('app.name') }}.

CONTACT DETAILS
---------------
Sender Name: {{ $contact->name }}
Phone / WhatsApp: {{ $contact->phone }}
Email: {{ $contact->email ?? '-' }}
Service: {{ $contact->service ?? '-' }}
From / Pickup: {{ $contact->from ?? '-' }}
To / Destination: {{ $contact->to ?? '-' }}
Submitted At: {{ $contact->created_at?->toDateTimeString() }}

Travel Details:
{{ $contact->details ?? '-' }}

Dashboard: {{ url('/admin/contacts') }}
