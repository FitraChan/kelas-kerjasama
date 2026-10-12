@extends('layouts.app2')
@section('main-content')


            <div class="row">
                <!-- ICON BG -->
                <div class="col-md-12 col-lg-12">
                    <div class="card mb-2">
                        <div class="card-body">
                            <h4 class="card-title mb-3">
                                Informasi Pembayaran</small>
                            </h4>




                            <div class="separator-breadcrumb border-top"></div>

                            <form action="{{ url('registrasi') }}" method="POST">
                            @csrf
                                                <div class="form-group col-md-6">
                                                <h5>Nama <span class="text-danger"></span></h5>
                                                <div class="controls">
                                                    <input type="text" name="name" id="start_date" class="form-control datepicker-autoclose" placeholder="Nama"> <div class="help-block"></div></div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                <h5>Email <span class="text-danger"></span></h5>
                                                <div class="controls">
                                                    <input type="text" name="email" id="end_date" class="form-control datepicker-autoclose" placeholder="Email"> <div class="help-block"></div></div>
                                                </div>

                                                <div class="form-group col-md-6">
                                                <h5>Password <span class="text-danger"></span></h5>
                                                <div class="controls">
                                                    <input type="text" name="password" id="password" class="form-control datepicker-autoclose" placeholder="Password"> <div class="help-block"></div></div>
                                                </div>


                                                <div class="text-left" style="
                                                margin-left: 15px;
                                                ">
                                                <button type="text" id="btnFiterSubmitSearch" class="btn btn-info">Submit</button>
                                                </div>
                                  </form>



                    </div>
                </div>
            </div>


        </div>








@endsection
