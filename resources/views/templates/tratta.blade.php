<?php $origine = $tratta->origine;
$destinazione = $tratta->destinazione;
$data_p = date_format(date_create($tratta->data_partenza), 'd-m H:i');
$data_a = date_format(date_create($tratta->data_arrivo), 'd-m H:i'); ?>
<div class='tratta'>
    <h3 class='title'>Tratta {{ $i + 1 }}</h3>
    <p class='intestazione'>da <span>{{ $origine->city }}</span> a
        <span>{{ $destinazione->city }}</span>
    </p>
    <p class='schedule'>partenza {{ $data_p }} / arrivo {{ $data_a }} </p>
</div>
