@extends('layouts.app2')

@section('main-content')
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="siswa-tab" data-toggle="tab" href="#siswa" role="tab">
      Informasi Siswa
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pembayaran-tab" data-toggle="tab" href="#pembayaran" role="tab">
      Informasi Pembayaran
    </a>
  </li>
  
  <li class="nav-item">
    <a class="nav-link" id="dokumen-tab" data-toggle="tab" href="#dokumen" role="tab">
      Informasi Dokumen
    </a>
  </li>
  
  
</ul>

<div class="tab-content" id="myTabContent">
  @include('livewire.tunggakan_mbc.tab-siswa')
  @include('livewire.tunggakan_mbc.tab-pembayaran')
  @include('livewire.tunggakan_mbc.dokumen')

</div>

@include('livewire.tunggakan_mbc.scripts')
@endsection
