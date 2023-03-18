@extends('layouts.index')
@section('title')
    Home
@endsection

@section('content')
<div class="pagetitle">
  <nav>
      <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/index">Home</a></li>
      </ol>
  </nav>
</div>

<div class="d-flex justify-content-around">
    <div class="card  mb-3 border border border-primary mx-5" style="max-width: 18rem; max-height: 10rem;">
      <div class="card-header text-bg-primary d-flex justify-content-between">
        PTB
        <a href="/ptb" class="btn-sm text-decoration-none text-light"><i class="fas fa-angle-right"></i></a>
      </div>
      <div class="card-body">
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
      </div>
    </div> 
    <div class="card  mb-3 border border border-primary mx-5" style="max-width: 18rem; max-height: 10rem;">
      <div class="card-header text-bg-primary d-flex justify-content-between">
        Akademik
        <a href="/akademik" class="btn-sm text-decoration-none text-light"><i class="fas fa-angle-right"></i></a>
      </div>
      <div class="card-body">
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
      </div>
    </div> 
    <div class="card  mb-3 border border border-primary mx-5" style="max-width: 18rem; max-height: 10rem;">
      <div class="card-header text-bg-primary d-flex justify-content-between">
        Karyawan
        <a href="/ptb" class="btn-sm text-decoration-none text-light"><i class="fas fa-angle-right"></i></a>
      </div>
      <div class="card-body">
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
      </div>
    </div> 
</div>

@endsection
