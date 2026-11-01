<x-mail::message>
# {{ $isDemo ? 'درخواست دیمو تازه' : 'پیام تازه از سایت' }}

**{{ $lead->name }}**

<x-mail::table>
| | |
|:--|:--|
| شماره تماس | {{ $lead->phone ?: '—' }} |
| ایمیل | {{ $lead->email ?: '—' }} |
@if ($isDemo)
| شرکت | {{ $lead->company ?: '—' }} |
| نوع تجارت | {{ $lead->business_type ? __('site.business_types.'.$lead->business_type, [], 'fa') : '—' }} |
| تعداد شعبه | {{ $lead->branches ?: '—' }} |
| زبان صفحه | {{ $lead->locale ?: '—' }} |
@else
| موضوع | {{ $lead->subject ?: '—' }} |
@endif
| زمان | {{ $lead->created_at->format('Y-m-d H:i') }} |
</x-mail::table>

@if ($isDemo ? $lead->message : $lead->message)
**{{ $isDemo ? 'توضیحات' : 'پیام' }}**

{{ $lead->message }}
@endif

<x-mail::button :url="$adminUrl">
دیدن در پنل مدیریت
</x-mail::button>

اگر این ایمیل را جواب بدهید و مشتری ایمیل داده باشد، جواب به خود او می‌رود. در غیر این صورت با شماره بالا تماس بگیرید.
</x-mail::message>
