<?php
wp_enqueue_style('bootstrap5', plugins_url( '/css/bootstrap.min.css', __FILE__ ));
wp_enqueue_script('jquery') ;
wp_enqueue_script( 'boot', plugins_url( '/js/bootstrap.bundle.min.js', __FILE__ ), array( 'jquery' ),'',true );
wp_enqueue_style('cdq-style', plugins_url( '/css/style.css', __FILE__ ) );
wp_enqueue_script('cdq-scripts', plugins_url( '/js/scripts.js', __FILE__ ) );
?>

<div id="cdq-wrap">

	<div class="container-fluid">
		
		<div class="row">

			<div class="col-12">

				<div  class="cdq-logo">

					<img src="<?php echo plugins_url('/images/cdq-icon-64.png', __FILE__); ?>">

					<h1>Custom Data Query</h1>

				</div>

			</div>

		</div>