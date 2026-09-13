New Enquiry — {{ $contact->created_at?->toDateTimeString() }}

Name:    {{ $contact->name }}
Phone:   {{ $contact->phone }}
Service: {{ $contact->service ?? '-' }}
@if($contact->field_a)
Field A: {{ $contact->field_a }}
@endif
@if($contact->details)

Details:
{{ $contact->details }}
@endif

{{ url('/admin/enquiries') }}
