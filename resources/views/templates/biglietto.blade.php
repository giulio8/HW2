<?php
$id = $volo->id;
$prezzo = $volo->prezzo;
$valuta = $volo->valuta;
if (isset($volo->bagaglio)) {
    $bagaglio = $volo->bagaglio;
}
$andata = $volo->andata;
$ritorno = $volo->ritorno;
$compagnia = $volo->compagnia;
$origine = $andata->tratte[0]->origine->city;
$destinazione = $andata->tratte[count($andata->tratte) - 1]->destinazione->city;
$n_scali = count($andata->tratte) - 1;
?>


<div class="biglietto">
    <p class="compagnia">{{ $compagnia }}</p>
    <p class="intestazione">da <span> {{ $origine }} </span> a <span>{{ $destinazione }} </span></p>
    <p class="scali">@if ($n_scali > 0) Scali: {{ $n_scali }} @else Volo diretto @endif</p>
    <p class="prezzo">Prezzo totale:
        <?php $parti = explode('.', $prezzo);
        $int = $parti[0];
        isset($parti[1]) ? ($dec = $parti[1]) : ($dec = '00');
        ?>
        <span class='int'>{{ $int }}</span>,<span class='decimal'>{{ $dec }}</span> <span
            class="currency">{{ $valuta }}</span> <span class="info">(oneri e tasse inclusi)</span>
    </p>


    <div class="details collapse" data-id="{{ $id }}">
        <hr>
        @if (isset($bagaglio))
            <p class="bagaglio">Bagagli inclusi: {{ $bagaglio }}</p>
        @endif
        <div class="container-tratte">
            <div class="andata">
                <h2 class="title">Andata</h2>
                @foreach ($andata->tratte as $i => $tratta)
                    @include('templates.tratta', ['tratta' => $tratta, 'i' => $i])
                @endforeach
            </div>
            <div class='ritorno'>
                <h2 class='title'>Ritorno</h2>
                @foreach ($ritorno->tratte as $i => $tratta)
                    @include('templates.tratta', ['tratta' => $tratta, 'i' => $i])
                @endforeach
            </div>
        </div>
    </div>
    <p class='show-details' data-bs-toggle='collapse' data-bs-target=".details[data-id='{{ $id }}']"
        aria-expanded='false'><img class='arrow-details' src='{{ asset("assets/caret-down.png") }}'><span class='text'>Mostra
            dettagli</span></p>
</div>
