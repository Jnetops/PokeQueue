@extends('layouts.app')

<link href="{{ asset('css/expired.css') }}" rel="stylesheet">

@section('content')
<div class="container error">
  <div class="row">
    <div class="col-md-12 text-center">
      <img src="{{url('/images/error-pikachu.png')}}" alt="Image" class="error-image" style="height: 300px; width: 50%;"/>
      <h2 class="text-center">{{$Type}} Has Expired</h2>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      @if ($Type == 'Group')
        <a class="btn btn-default" id="redirect" href="/groups?distance=25">View All Groups</a>
      @elseif ($Type == 'Event')
        <a class="btn btn-default" id="redirect" href="/events?distance=25">View All Events</a>
      @elseif ($Type == 'Raid')
        <a class="btn btn-default" id="redirect" href="/raid/tracker?distance=25">View All Raids</a>
      @elseif ($Type == 'Pokemon')
        <a class="btn btn-default" id="redirect" href="/poke/tracker?distance=25">View All Pokemon</a>
      @endif
    </div>
  </div>
</div>
@endsection
