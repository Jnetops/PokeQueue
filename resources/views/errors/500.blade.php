@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
      <img src="{{url('/images/error-pikachu.png')}}" alt="Image" class="error-image" style="height: 300px; width: 50%;"/>
      <h2 class="text-center">500 - Internal Server Error</h2>
      <h4><b>
        If you received this message in error, contact the server administrator at
        <a href="mailto:pokequeue@gmail.com">Pokequeue@gmail.com</a>
         for assistance.
        Please include the time the error occured, Thank you.
      </b></h4>
    </div>
  </div>
</div>
@endsection
