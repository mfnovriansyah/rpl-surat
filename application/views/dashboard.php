<?php 
$chartScript ='';
if(!isset($scriptBeforeJQuery)){$scriptBeforeJQuery = '';}
if(!isset($scriptAfterJQuery)){$scriptAfterJQuery = '';}
if(!isset($scriptFooter)){$scriptFooter = '';}
$this->load->view('parts/header',['add' => $scriptBeforeJQuery, 'add2' => $scriptAfterJQuery]); ?>
    <!-- Main content -->
    <section class="content">


    </section>
    <!-- /.content -->
<?php 
$data = ["add" => "<!-- ChartJS 1.0.1 -->
<script src='".asset_url('plugins/chartjs/Chart.min.js')."'></script>
<script src='".asset_url('dist/js/pages/dashboard2.js')."'></script><script>".$chartScript."</script>"];
$this->load->view('parts/footer',$data); ?>
