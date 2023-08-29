@component('mail::message')
# Dear Admin

Ada pembayaran baru dari <strong>{{ $name }}</strong> sejumlah <strong>{{ "Rp " . number_format($amount,2,',','.') }}</strong>, yang ditansfer pada tanggal <strong>{{ date('d F Y', strtotime($transfer_date)) }}</strong> dari Bank <strong>{{ $bank }}</strong>. Berikut ini bukti transfer dari <strong>{{ $name }}</strong>, mohon diperiksa ya.

Thanks,<br>
{{ config('app.name') }}
@endcomponent