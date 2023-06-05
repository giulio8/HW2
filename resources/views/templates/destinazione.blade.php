<div class="destination">
    <div class="reveal from-left gallery-image" data-title="{{$title}}">
        <div class="buttons">
            <button class="elimina" data-title="{{$title}}">Elimina</button>
            <button class="trova-voli" data-title="{{$title}}">Trova voli</button>
        </div>
        <img src="{{asset('media/'.$image)}}">
    </div>
    <div class="text">
        <h3 class="title reveal from-right">{{$title}}</h3>
        <p class="descrizione reveal from-down">{{$description}}
        </p>
    </div>
</div>