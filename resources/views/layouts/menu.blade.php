<div class="side-content-wrap">
    <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
        <ul class="navigation-left">

        
            <!-- <li class="nav-item {{ request()->is('starter/*') ? 'active' : '' }}" data-item="starter">
                <a class="nav-item-hold" href="#">
                    <i class="nav-icon i-Bar-Chart"></i>
                    <span class="nav-text">Starter pages</span>
                </a>
                <div class="triangle"></div>
            </li> -->

            <li class="nav-item ">
              <a class="nav-item-hold {{ Route::currentRouteName()=='dashboard' ? 'active' : '' }}" href='/post' onclick="loading()">
                  <i class="nav-icon i-Bar-Chart"></i>
                  <span class="item-name">Dashboard</span>
              </a>
          </li> 
          
           
         
            <li class="nav-item ">
                <a class="nav-item-hold {{ Route::currentRouteName()=='mahasiswa' ? 'active' : '' }}" href="{{ route('mahasiswa.index') }}" onclick="loading()">
                    <i class="nav-icon i-Students"></i>
                    <span class="item-name">Mahasiswa</span>
                </a>
            </li>

            <li class="nav-item ">
                <a class="nav-item-hold {{ Route::currentRouteName()=='alumni' ? 'active' : '' }}" href="{{ route('status-mahasiswa.index') }}" onclick="loading()">
                    <i class="nav-icon i-Students"></i>
                    <span class="item-name">Status Mahasiswa</span>
                </a>
            </li>



            <li class="nav-item ">
                <a class="nav-item-hold {{ Route::currentRouteName()=='keuangan' ? 'active' : '' }}" href="{{ route('keuangan_mbc.index') }}" onclick="loading()">
                    <i class="nav-icon i-Clock-3"></i>
                    <span class="item-name">Keuangan Mbc</span>
                </a>
            </li>


             <li class="nav-item ">
                <a class="nav-item-hold {{ Route::currentRouteName()=='master' ? 'itemBayar' : '' }}" href="{{ route('itemBayar.index') }}" onclick="loading()">
                    <i class="nav-icon i-Clock-3"></i>
                    <span class="item-name">Item Bayar</span>
                </a>
            </li>
            
             <li class="nav-item ">
                <a class="nav-item-hold {{ Route::currentRouteName()=='master' ? 'itemBayar' : '' }}" href="{{ route('thn_ajaran.index') }}" onclick="loading()">
                    <i class="nav-icon i-Clock-3"></i>
                    <span class="item-name">Tahun Ajaran</span>
                </a>
            </li>
            
            
             <li class="nav-item ">
                <a class="nav-item-hold {{ Route::currentRouteName()=='master' ? 'ipaymu' : '' }}" href="{{ route('ipaymu.index') }}" onclick="loading()">
                    <i class="nav-icon i-Clock-3"></i>
                    <span class="item-name">List Ipaymu</span>
                </a>
            </li>


            

            
         

         

           


        </ul>
    </div>


  
    <div class="sidebar-overlay"></div>
</div>
<!--=============== Left side End ================-->

<script>
    function loading() {

        showLoading();

    }
</script>