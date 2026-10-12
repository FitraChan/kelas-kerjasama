<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>{{ config('app.name', 'Admin Pembayaran ITB STIKOM BALI') }}</title>

  
	
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('public/css/app.css') }}">


    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />

    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" />
    <link href="{{ asset('public/design/vendor/css/plugins/perfect-scrollbar.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <link href="{{ asset('public/design/vendor/css/themes/lite-purple.min.css') }}" rel="stylesheet" />


    <link href="{{ asset('public/design/vendor/css/themes/lite-blue.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/design/vendor/css/bootstrap-rtl.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/design/vendor/css/plugins/smart.wizard/smart_wizard_theme_arrows.min.css') }}" rel="stylesheet" />


    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    
      <style>
      
      .header-part-right .badge {
            position: absolute;
            top: -5px;
            right: -8px;
            font-size: 10px;
            padding: 3px 6px;
        }

            .loading {
          z-index: 20;
          position: absolute;
          top: 0;
          left:-5px;
          width: 100%;
          height: 100%;
          background-color: rgba(0,0,0,0.4);
          }
          .loading-content {
          position: absolute;
          border: 16px solid #f3f3f3; /* Light grey */
          border-top: 16px solid #3498db; /* Blue */
          border-radius: 50%;
          width: 50px;
          height: 50px;
          top: 40%;
          left:35%;
          animation: spin 2s linear infinite;
          }

          @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
          }
    </style>


    <!-- Scripts -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body>
    <div class="app-admin-wrap layout-sidebar-large">
        <!--Header-->
        <div class="main-header">
            <div class="">
                <img src="{{ asset('/public/asset/images/logo_stikom.png') }}" style="width: 70px; margin-left: 20px;"  title="Logo">
            </div>

            <div class="menu-toggle">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div style="margin: auto"></div>

            <div class="header-part-right d-flex align-items-center">

    <!-- Message Icon -->
    <div class="dropdown mr-3">
        <a href="{{ route('ipaymu.index') }}">
            <i class="i-Speach-Bubble-3 text-muted" style="font-size: 20px;"></i>
        @if($ipaymuPendingCount > 0)
            <span class="badge badge-danger">{{ $ipaymuPendingCount }}</span>
        @endif
        </a>
    </div>

    <!-- User avatar dropdown -->
    <div class="dropdown">
        <div class="user col align-self-end">
            <img src="{{ asset('/public/asset/images/faces/1.jpg') }}"
                 id="userDropdown"
                 alt=""
                 data-toggle="dropdown"
                 aria-haspopup="true"
                 aria-expanded="false">

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <div class="dropdown-header">
                    <i class="i-Lock-User mr-1"></i> Akun
                </div>

                <a class="dropdown-item" href="#">Update Profile</a>

                <div class="dropdown-item p-0">
                    <form method="POST" action="{{ url('logout') }}">
                        @csrf
                        <button class="btn btn-link btn-block text-left">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

        </div>
        <!--End Header-->

        @include('layouts.menu')

        <div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->




            <div class="breadcrumb">
                <div class="modal-header">
                    <ul>
                        <li><a href="">Selamat Datang</a></li>
                        <li> </li>
                    </ul>
                </div>
            </div>

            <br />
            
      

            @yield('main-content')
        
    
            
            
       
           
            



            <!-- Footer Start -->
            <div class="flex-grow-1"></div>
            <div class="app-footer">
                <div class="footer-bottom border-top pt-3 d-flex flex-column flex-sm-row align-items-center">
                    <span class="flex-grow-1"></span>
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="m-0">© 2021 ITB STIKOM BALI | All rights reserved</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- fotter end -->
        </div>

        </div>
        
        @stack('modals')

        <!-- Bootstrap core JavaScript -->


    
        <script src="{{ asset('public/js/app.js') }}"></script>
        <script src="{{ asset('public/vendor/js/plugins/jquery-3.3.1.min.js') }}"></script>
                <!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->






        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>

        <script src="{{ asset('public/vendor/js/plugins/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('public/vendor/js/plugins/perfect-scrollbar.min.js') }}"></script>
        <script src="{{ asset('public/vendor/js/scripts/script.min.js') }}"></script>
        <script src="{{ asset('public/design/vendor/js/scripts/sidebar.large.script.min.js') }}"></script>

        <script src="{{ asset('public/design/vendor/js/scripts/datatables.script.min.js') }}"></script>
        <script src="{{ asset('public/vendor/js/scripts/customizer.script.min.js') }}"></script>
        <script src="{{ asset('public/asset/dist-assets/js/plugins/bootstrap.bundle.min.js') }}"></script>

        <script src="{{ asset('public/js/currency.js') }}"></script>
        
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

          <script src="https://cdn.datatables.net/fixedcolumns/3.3.2/js/dataTables.fixedColumns.min.js"></script>

	    <script>

        function showLoading() {
          document.querySelector('#loading').classList.add('loading');
          document.querySelector('#loading-content').classList.add('loading-content');
        }

        function hideLoading() {
          document.querySelector('#loading').classList.remove('loading');
          document.querySelector('#loading-content').classList.remove('loading-content');
        }

        </script>
        
          <style>
              #tab-list .close {
      	         margin-left: 7px;
      	}


        </style>
        <script>
        $(document).ready(function() {
              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });

    $('#tab-list').on('click', '.close', function() {

          var tabID = $(this).parents('a').attr('href');


           $(this).parents('li').remove();
           $(tabID).remove();

           var tabFirst = $('#tab-list a:first');

            tabFirst.tab('show');

    });

    var list = document.getElementById("tab-list");
});

			function new_tab(uri,send) {


			var url = "https://pembayaran.stikom-bali.ac.id/admin/"+uri;


			var tab_ = $('li.nav-item.'+uri);
			//console.log(tab_);
			 if(tab_.length >=1) {
			     $('.nav-tabs li.nav-item.'+uri+' a').click();
			     return false;
			 }
			 

			  var judul = uri.replaceAll('_', ' ');
 			 $('#tab-list').append($('<li class="nav-item '+uri+'"><a class="nav-link" href="#'+uri+'" role="tab" data-toggle="tab">'+judul.toUpperCase()+'<button class="close" id="closit" type="button" title="Remove this page">×</button>' + '</li>'));


			  $.ajax({
			    url: url,
			    type: send,

			  }).done(function(data) {

				 $('#tab-content').append($('<div class="tab-pane" id="'+uri+'">'+ data + '</div>'));
				 $('.nav-tabs li.nav-item.'+uri+' a').click();
				     return false;
			  });

			}

        </script> 



</body>

</html>
