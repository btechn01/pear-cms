@extends('partials.layout')

@section('content')
<div class="third-page">
  <div class="half-page-content mt-20">
    <h4 class="text-header text-upper">
      My Tickets
    </h4>
    <p class="text-beige col-md-6 col-center">
      Pick a character to see its tickets.
    </p>
  </div>
</div>
<div class="container col-md-6 mt-20">
<div class="row">
  <div class="col-md-12 mt-20 no-padding">
  <h5 class="border-bottom text-grey mb-10">
    Select a character
  </h5>
</div>

@foreach(Helpers::getAccountCharacters() as $character)
<a class="button border orange col-sm-5" href="{{ url('/acp/character/tickets') }}/{{ $character->name }}">
  <img alt="{{ $character->name }}" src="{{ asset('images/races') }}/{{ $character->race }}_{{ $character->gender }}.jpg" /> {{ $character->name }}
</a>
@endforeach
@if (Helpers::getAccountCharactersNumbers() == 0)
<p class="text-red">
  You don't have any characters!
</p>
@endif
</div>
</div>
@endsection
