<?php include_once( 'Init/Init.php'); ?>

<script type='text/javascript' src='<?php echo Init::$rootUrl; ?>public/js/jquery.js'></script>
<body>
</body>

<?php
DMP( Init::$config , 'Db config');
DMP( 'some text');
Init::newController( 'Router' );
?>
