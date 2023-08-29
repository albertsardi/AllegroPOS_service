<!DOCTYPE html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Allegro - ERP System Administrator</title>
<meta name="description" content="Allegro - ERP System Administrtor">
<meta name="author" content="Albert - (c)ASAfoodenesia">

<html lang="en">

<head>
    <!-- Favicon -->
    <link rel="shortcut icon" href="public/assets/images/favicon.ico">
    <!-- BEGIN CSS for this page -->
    {{ HTML::style("public/assets/css/bootstrap.min.css") }}
    {{ HTML::style("assets/metro/css/metro-all.min.css") }}
    {{ HTML::style("public/assets/css/fontawesome/font-awesome.min.css") }}
    {{ HTML::style("public/assets/select2/select2.min.css") }}
    {{ HTML::style("public/assets/css/style.css") }}
    <!-- END CSS for this page -->
    <style>
        .nav-link  {font-size: 20px;}
		.card {
        	margin: 0 auto;
        	float: none;
        	margin-bottom: 10px;
		}
		#tabs a {color:gray;}
		#tabs a.active {color:black;}
        .tab-content{
             background-color: #f3f3f3;
            border-top-style: none;
            border-right-style: solid;
            border-bottom-style: solid;
            border-left-style: solid;
            border-width:1px;border-color:lightgray;
            padding: 20px;
            height: 350px;
        }
    </style>
</head>

<!--<body class="adminbody widescreen" ng-controller="formCtrl">-->

<body>

    <div id="main">

        <!-- top bar navigation -->
        @include('topmenu')
        <!-- End Navigation -->

        <!-- Left Sidebar -->
        @include('menu')
        <!-- End Sidebar -->


        <div class="content-page">

            <!-- Start content -->
            <div class="content">

                <div class="container-fluid">

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="breadcrumb-holder">
                                <h1 class="main-title float-left">{{$header}}</h1>
                                <ol class="breadcrumb float-right">
                                    <li class="breadcrumb-item">Home</li>
                                    <li class="breadcrumb-item active">Forms</li>
                                </ol>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <form action="ajaxPost/product" method="post">
                        <!-- panel button -->
                        @include('buttonpanel',['jr'=>$jr])

                        <!-- <form  method='post'> -->
                        <!-- <input name='formtype' value='product' > -->
                        {{ Form::hidden('formtype',$jr) }}
                        <input type="hidden" name="_token" value="<?php echo csrf_token() ?>">

                        <div class="row">
                            @yield('content')
                        </div>

                        <div class="row">
                            @yield('tab')
                        </div>

                        <div class='row'>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                Created [CreatedDate] by [CreatedBy]
                            </div>
                        </div>

                    </form>

                </div>
                <!-- END container-fluid -->



            </div>
            <!-- END content -->

        </div>
        <!-- END content-page -->

        <footer class="footer">
            <? #require 'footer.php' 
            ?>
        </footer>

    </div>
    <!-- END main -->

    <!-- BEGIN Java Script for this page -->
    {{ HTML::script("public/assets/js/jquery.min.js") }}
    {{ HTML::script("public/assets/js/fastclick.js") }}
    {{ HTML::script("public/assets/js/pikeadmin.js") }}
    {{ HTML::script("public/assets/js/bootstrap.min.js") }}
    {{ HTML::script("public/assets/js/bootbox.min.js") }}
    {{ HTML::script("public/assets/select2/select2.min.js") }}
    {{ HTML::script("public/assets/metro/js/metro.min.js") }}
    {{ HTML::script("public/assets/js/helper_metroform.js") }}
    @yield('js')

    <!-- END Java Script for this page -->

</body>

</html>

<!-- Modal -->
<!-- insert modal function HERE -->
{{ $modal }}
<script>
    {
        {{ $jsmodal }}
    }
</script>
<!-- End Modal -->



