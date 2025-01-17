 <!-- ============================================================== -->
 <!-- Start right Content here -->
 <!-- ============================================================== -->
 <div class="main-content">
     <div class="page-content">
         <div class="container-fluid">

             <!-- start page title -->
             <div class="row">
                 <div class="col-12">
                     <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                         @yield('title')
                         {{-- <h4 class="mb-sm-0">Starter 123</h4>
                         <div class="page-title-right">
                             <ol class="breadcrumb m-0">
                                 <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                                 <li class="breadcrumb-item active">Starter</li>
                             </ol>
                         </div> --}}

                     </div>
                 </div>
             </div>
             <!-- end page title -->
             @yield('content')

         </div>
         <!-- container-fluid -->
     </div>
     <!-- End Page-content -->
