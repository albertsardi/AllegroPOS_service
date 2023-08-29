<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Allegro table - ERP System Administrator</title>
<meta name="description" content="Allegro - ERP System Administrator">
<meta name="author" content="Albert - (c)ASAfoodenesia">

<html lang="en" ng-app="myApp">

<head>
    <!-- Favicon -->
    <link rel="shortcut icon" href="public/assets/images/favicon.ico">
    <!-- BEGIN CSS for this page -->
    {{ HTML::style("public/assets/css/bootstrap.min.css") }}
    {{ HTML::style("public/assets/css/fontawesome/font-awesome.min.css") }}
    {{ HTML::style("public/metro/css/metro-all.css") }}
    {{ HTML::style("public/assets/css/style.css") }}
    {{ HTML::style("public/assets/select2/select2.min.css") }}
    {{ HTML::style("public/assets/css/datepicker.css") }} 
    <style>
        .border {
            border-style: solid;
        }

        .plabel {
            width: 120px;
            vertical-align: middle;
            margin: 3px 8px 3px 8px;
        }

        .cell {
            margin: 2px 2px 2px 2px;
            float: left;
            display: inline-block
        }

        input.w5 {
            width: 50px;
        }

        input.w10 {
            width: 100px;
        }

        input.w20 {
            width: 200px;
        }

        input.w30 {
            width: 300px;
        }

        input.w40 {
            width: 400px;
        }

        /*.gridLookup, .gridLookup th, .gridLookup td {border: 1px solid gray;padding:5px;}
    .gridLookup {width:100%;} */

        /* * {font-size:12px;} */
        /* input{font-size:12px;background-color:red;} */
        div.grow {
            border: 0px solid red;
            width: 1300px;
            display: inline-block;
        }

        div.grid-container,
        div.dialog-content {
            xoverflow: hidden;
            overflow: auto;
        }
       
    </style>
    <!-- END CSS for this page -->

</head>

<body ng-controller="formCtrl">

    <div id="main">

        <!-- top bar navigation -->
        @extends('topmenu')
        <!-- End Navigation -->

        <!-- Left Sidebar -->
        @extends('menu')
        <!-- End Sidebar -->


        <div class="content-page">
            <div class='result'>...</div>

            <!-- Start content -->
            <div class="content">

                <div class="container pull-left">

                    <div class="breadcrumb-holder" style="height:70px; ">
                        <h1 class="main-title float-left">Forms Table</h1>
                        <span class="float-right">Trans / Form</span>
                    </div>

                    <form action="ajaxPost" method="post">
                        {{ Form::hidden('formtype', $jr) }}
                        {{ Form::hidden('_token', csrf_token()) }}

                        <!-- panel button -->
                        @include('buttonpanel',['jr'=>$jr])

                        @yield('header')

                        <!-- GRID -->
                        @yield('grid')

                        <!-- Footer -->
                        @yield('footer')

                    </form>
                    <!-- end Form -->

                    <div class='row'>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                            Created 2018-09-03 By admin
                        </div>
                    </div>

                </div>

            </div>
            <!-- END content -->

        </div>
        <!-- END content-page -->

        <!-- Begin Footer -->
        <!-- End Footer -->

    </div>
    <!-- END main -->


    <!-- BEGIN Java Script for this page -->
    {{ HTML::script("public/assets/js/jquery.min.js") }}
    {{ HTML::script("public/assets/js/bootstrap.min.js") }}
    {{ HTML::script("public/metro/js/metro.min.js") }}
    {{ HTML::script("public/assets/js/pikeadmin.js") }}
    {{ HTML::script("public/assets/js/bootbox.min.js") }}
    {{ HTML::script("public/assets/js/fastclick.js") }}
    <!-- {{ HTML::script("assets/js/datepicker.js") }} -->
    {{ HTML::script("public/assets/select2/select2.min.js") }}
    {{ HTML::script("public/assets/js/helper_metroform.js") }}
    <!-- {{ HTML::script("public/assets/js/helper_metroeditgrid.js") }} -->


</body>

</html>

<!-- Modal -->
<!-- insert modal function HERE -->
{!! $modal !!}
{!! $jsmodal !!}
<!-- <div id="demoDialog1" class="dialog" data-role="dialog">
    <div class="dialog-title">Use Windows location service?</div>
    <div class="dialog-content">
        Bassus abactors ducunt ad triticum.
        A fraternal form of manifestation is the bliss.
    </div>
    <div class="dialog-actions">
        <button class="button js-dialog-close">Disagree</button>
        <button class="button primary js-dialog-close">Agree</button>
    </div>
</div> -->
<!-- End Modal -->


<!-- JQuery SCRIPT -->
<!-- JS -->
@yield('js')



<!-- Form js -->
<script>
    ////form format (temp)
    function cnum(v) {
        v = parseInt(v);
        if (isNaN(v)) v = 0;
        return v;
    }
</script>







<!-- END Java Script for this page -->

</body>

</html>