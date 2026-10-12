@extends('livewire.tagihan_admin_pdf.layout')

@section('content')
  @include('livewire.tagihan_admin_pdf.header')
  @include('livewire.tagihan_admin_pdf.table_item')
  @include('livewire.tagihan_admin_pdf.table_tahun')
  @include('livewire.tagihan_admin_pdf.signature')

  @if(is_null($status_mahasiswa))
      @include('livewire.tagihan_admin_pdf.keterangan')
  @endif
@endsection
