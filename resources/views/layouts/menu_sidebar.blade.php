 <!-- ========== App Menu ========== -->
 <div class="app-menu navbar-menu">
     <!-- LOGO -->
     <div class="navbar-brand-box">
         <!-- Dark Logo-->
         <a href="/" class="logo logo-dark">
             <span class="logo-sm">
                 <img src="{{ asset('assets') }}/images/logo-sm.png" alt="" height="22">
             </span>
             <span class="logo-lg">
                 <img src="{{ asset('assets') }}/images/logo-dark.png" alt="" height="17">
             </span>
         </a>
         <!-- Light Logo-->
         <a href="/" class="logo logo-light">
             <span class="logo-sm">
                 <img src="{{ asset('assets') }}/images/logo-sm.png" alt="" height="22">
             </span>
             <span class="logo-lg">
                 <img src="{{ asset('assets') }}/images/logo-light.png" alt="" height="17">
             </span>
         </a>
         <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
             id="vertical-hover">
             <i class="ri-record-circle-line"></i>
         </button>
     </div>

     <div id="scrollbar">
         <div class="container-fluid">

             <div id="two-column-menu">
             </div>
             <ul class="navbar-nav" id="navbar-nav">


                 @if (auth()->check() &&
                         auth()->user()->hasRole(['super_admin']))
                     <li class="nav-item">
                         <a class="nav-link menu-link" href="/dashboard">
                             <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
                         </a>
                     </li>


                     <li class="menu-title"><span data-key="t-menu">Processing</span></li>

                     <li class="nav-item">
                         <a class="nav-link menu-link" href="/receiving">
                             <i class="ri-share-forward-2-line"></i> <span data-key="t-receiving">Receiving</span>
                         </a>
                     </li>

                     {{-- @if (auth()->user()->hasAnyRole(['super_admin', 'cutting'])) --}}
                     <li class="nav-item">
                         <a class="nav-link menu-link" href="/cutting">
                             <i class="ri-knife-blood-line"></i> <span data-key="t-cutting">Cutting/Trimming</span>
                         </a>
                     </li>
                     {{-- @endif --}}

                     {{-- @if (auth()->user()->hasAnyRole(['super_admin', 'retouching'])) --}}
                     <li class="nav-item">
                         <a class="nav-link menu-link" href="/retouching">
                             <i class="ri-dashboard-line"></i> <span data-key="t-retouching">RTC/Timbang Ulang</span>
                         </a>
                     </li>
                     {{-- @endif --}}

                     <li class="nav-item">
                         <a class="nav-link menu-link" href="/packing">
                             <i class=" ri-inbox-archive-fill"></i> <span data-key="t-retouching">Packing</span>
                         </a>
                     </li>
                 @endif


                 {{--   @if (auth()->user()->hasAnyRole(['super_admin', 'qc']))
                     <li class="menu-title"> <span data-key="t-pages">Chechking</span></li>

                     <li class="nav-item">
                         <a class="nav-link menu-link" href="#users" data-bs-toggle="collapse" role="button"
                             aria-expanded="false" aria-controls="users">
                             <i class="ri-checkbox-line"></i> <span data-key="t-users">Checking</span>
                         </a>
                         <div class="collapse menu-dropdown" id="users">
                             <ul class="nav nav-sm flex-column">
                                 <li class="nav-item">
                                     <a href="/receiving-checking" class="nav-link" data-key="t-starter">Receiving
                                         Checking
                                     </a>
                                 </li>
                                 <li class="nav-item">
                                     <a href="/cutting-checking" class="nav-link" data-key="t-starter">Cutting
                                         Checking</a>
                                 </li>
                                 <li class="nav-item">
                                     <a href="/retouching-checking" class="nav-link" data-key="t-starter">Retouching
                                         Checking</a>
                                 </li>
                                 <li class="nav-item">
                                     <a href="/packing-checking" class="nav-link" data-key="t-starter">Packing
                                         Checking</a>
                                 </li>
                             </ul>
                         </div>
                     </li>
                 @endif

                 --}}

                 <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-inspection">Quality
                         Control</span>
                 </li>

                 <li class="nav-item">
                     <a class="nav-link menu-link" href="/inspection">
                         <i class="ri-list-check-2"></i> <span>Inspection</span>
                     </a>
                 </li>

                 <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-inspection">Traceability</span>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link menu-link" href="/forward-traceability">
                         <i class="ri-list-check-2"></i> <span>Forward Traceability</span>
                     </a>
                 </li>
                 {{-- <li class="nav-item">
                     <a class="nav-link menu-link" href="/backward-traceability">
                         <i class="ri-list-check-2"></i> <span>Backward Traceability</span>
                     </a>
                 </li> --}}

                 @if (auth()->check() &&
                         auth()->user()->hasRole(['super_admin']))
                     <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Ecommerce</span>
                     </li>

                     <li class="nav-item">
                         <a class="nav-link menu-link" href="/order/list-order">
                             <i class="ri-list-check-2"></i> <span>List Order</span>
                         </a>
                     </li>
                 @endif

                 @if (auth()->check() &&
                         auth()->user()->hasRole(['super_admin']))
                     <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Pengaturan</span>
                     </li>

                     <li class="nav-item">
                         <a class="nav-link menu-link" href="/supplier">
                             <i class="ri-group-line"></i> <span data-key="t-supplier">Supplier</span>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link menu-link" href="/product">
                             <i class="ri-file-list-2-line"></i> <span data-key="t-supplier">Produk</span>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a class="nav-link menu-link" href="/grades">
                             <i class=" ri-list-settings-line"></i> <span data-key="t-supplier">Grade</span>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a class="nav-link menu-link" href="#users" data-bs-toggle="collapse" role="button"
                             aria-expanded="false" aria-controls="users">
                             <i class="ri-pages-line"></i> <span data-key="t-users">Users</span>
                         </a>
                         <div class="collapse menu-dropdown" id="users">
                             <ul class="nav nav-sm flex-column">
                                 <li class="nav-item">
                                     <a href="/users/customers" class="nav-link" data-key="t-starter">Data
                                         Customer</a>
                                 </li>
                                 <li class="nav-item">
                                     <a href="/users" class="nav-link" data-key="t-starter">Data User</a>
                                 </li>
                                 <li class="nav-item">
                                     <a href="/roles" class="nav-link" data-key="t-starter">Role</a>
                                 </li>

                             </ul>
                         </div>
                     </li>
                 @endif

                 @if (auth()->check() &&
                         auth()->user()->hasRole(['customer', 'super_admin']))
                     <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Info</span>
                     <li class="nav-item">
                         <a class="nav-link menu-link" href="/product/list-product">
                             <i class="ri-shopping-cart-line"></i> <span data-key="t-produk">Produk</span>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link menu-link" href="/cart">
                             <i class="ri-shopping-basket-line"></i> <span data-key="t-cart">Cart</span>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link menu-link" href="/order">
                             <i class="ri-file-list-3-line"></i> <span data-key="t-order">Order</span>
                         </a>
                     </li>
                 @endif

                 <hr class="my-0">
                 <li class="nav-item">
                     <a class="nav-link menu-link" href="/logout">
                         <i class="ri-logout-circle-r-line"></i> <span data-key="t-supplier">Logout</span>
                     </a>
                 </li>
             </ul>
         </div>
         <!-- Sidebar -->
     </div>

     <div class="sidebar-background"></div>
 </div>
 <!-- Left Sidebar End -->
 <!-- Vertical Overlay-->
 <div class="vertical-overlay"></div>
