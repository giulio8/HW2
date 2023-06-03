<?php $partenza = $tratta->partenza;
$arrivo = $tratta->arrivo;
$data_p = date_format(date_create($partenza->at), 'd-m H:i');
$data_a = date_format(date_create($arrivo->at), 'd-m H:i'); ?>
<div class='tratta'>
    <h3 class='title'>Tratta {{ $i + 1 }}</h3>
    <p class='intestazione'>da <span>{{ $partenza->city }}</span> a
        <span>{{ $arrivo->city }}</span>
    </p>
    <p class='schedule'>partenza {{ $data_p }} / arrivo {{ $data_a }} </p>
</div>
