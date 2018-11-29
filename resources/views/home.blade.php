@extends('user.layout.app')

@section('content')
<div class="row banner">
  <div class="col-sm-12 text-center">
    <h1> Multi-purpose Live Video System Monitor</h1>
    <h3>Monitor Page which collects real time channel information.</h3>
    <h3>Monitoring of Video and Audio Signals.</h3>
  </div>
  <div class="col-sm-12 margin-t-15 text-center">
    <img src="{{ URL::asset('/images/mac.png') }}" alt="igolgi Monitor">
  </div>
</div>

<div class="row">
  <div class="col-sm-12">

    <div class="col-sm-12 main-content text-center">
      <h2 class="margin-b-15">{{ config('app.name', 'Multi-purpose Live Video System Monitor') }}</h2>
      <p class="margin-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc fermentum sed odio et elementum. Pellentesque elementum,
        eros eget faucibus lobortis, diam lectus pellentesque sem, sit amet placerat dui ex ac sapien. Mauris pretium
        dapibus ante ac bibendum. Vestibulum accumsan leo nulla, consequat tristique orci semper eu. Orci varius natoque
        penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce vulputate ultricies ante, vel congue
        est aliquam ut. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Curabitur
        commodo, turpis a ultricies convallis, felis enim maximus magna, vitae tincidunt felis urna lobortis libero.
        Duis consectetur vel lectus quis fermentum. Donec a ipsum erat. Etiam commodo ex eu mi dapibus, faucibus finibus
        odio dapibus. Curabitur blandit velit ex, eu facilisis tortor consectetur quis. Maecenas ultricies tempus suscipit.
        Mauris nec lobortis risus, vitae blandit sapien.</p>
    </div>
  </div>

</div>
@endsection


