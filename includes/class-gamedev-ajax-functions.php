<?php
/**
 * Class _Ajax_Functions - class-gamedev-ajax-functions.php
 *
 * @author   Jake Evans
 * @category Admin
 * @package  Includes
 * @version  6.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '_Ajax_Functions', false ) ) :
	/**
	 * _Ajax_Functions class. Here we'll do things like enqueue scripts/css, set up menus, etc.
	 */
	class _Ajax_Functions {

		/**
		 * Class Constructor - Simply calls the Translations
		 */
		public function __construct() {


		}

	}
endif;

/*



function gamedev__settings_action_javascript() { 
	?>
  	<script type="text/javascript" >
  	"use strict";
  	jQuery(document).ready(function($) {

  		$("#gamedev--img-remove-1").click(function(event){
  			$('#gamedev--preview-img-1').attr('src', '<?php echo ROOT_IMG_ICONS_URL ?>'+'book-placeholder.svg');
  		});

  		$("#gamedev--img-remove-2").click(function(event){
  			$('#gamedev--preview-img-2').attr('src', '<?php echo ROOT_IMG_ICONS_URL ?>'+'book-placeholder.svg');
  		});



	  	$("#gamedev--save-settings").click(function(event){

	  		$('#gamedev--success-div').html('');
	  		$('#gamedev-spinner-storfront-lib').animate({'opacity':'1'});

	  		var callToAction = $('#gamedev--call-to-action-input').val();
	  		var libImg = $('#gamedev--preview-img-1').attr('src');
	  		var bookImg = $('#gamedev--preview-img-2').attr('src');

		  	var data = {
				'action': 'gamedev__settings_action',
				'security': '<?php echo wp_create_nonce( "gamedev__settings_action_callback" ); ?>',
				'calltoaction':callToAction,
				'libimg':libImg,
				'bookimg':bookImg			
			};
			console.log(data);

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {

			    	$('#gamedev-spinner-storfront-lib').animate({'opacity':'0'});
			    	$('#gamedev--success-div').html('<span id="gamedev-add-book-success-span">Success!</span><br/><br/> You\'ve saved your  Settings!<div id="gamedev-addstylepak-success-thanks">Thanks for using WPBooklist! If you happen to be thrilled with GameDev, then by all means, <a id="gamedev-addbook-success-review-link" href="https://wordpress.org/support/plugin/gamedev/reviews/?filter=5">Feel Free to Leave a 5-Star Review Here!</a><img id="gamedev-smile-icon-1" src="http://evansclienttest.com/wp-content/plugins/gamedev/assets/img/icons/smile.png"></div>')
			    	console.log(response);
			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});

			event.preventDefault ? event.preventDefault() : event.returnValue = false;
	  	});
	});
	</script>
	<?php
}


function gamedev__settings_action_callback(){
	global $wpdb;
	check_ajax_referer( 'gamedev__settings_action_callback', 'security' );
	$call_to_action = filter_var($_POST['calltoaction'],FILTER_SANITIZE_STRING);
	$lib_img = filter_var($_POST['libimg'],FILTER_SANITIZE_URL);
	$book_img = filter_var($_POST['bookimg'],FILTER_SANITIZE_URL);
	$table_name = _PREFIX.'gamedev_jre__options';

	if($lib_img == '' || $lib_img == null || strpos($lib_img, 'placeholder.svg') !== false){
		$lib_img = 'Purchase Now!';
	}

	if($book_img == '' || $book_img == null || strpos($book_img, 'placeholder.svg') !== false){
		$book_img = 'Purchase Now!';
	}

	$data = array(
        'calltoaction' => $call_to_action, 
        'libraryimg' => $lib_img, 
        'bookimg' => $book_img 
    );
    $format = array( '%s','%s','%s'); 
    $where = array( 'ID' => 1 );
    $where_format = array( '%d' );
    echo $wpdb->update( $table_name, $data, $where, $format, $where_format );


	wp_die();
}


function gamedev__save_default_action_javascript() { 

	$trans1 = __("Success!", 'gamedev');
	$trans2 = __("You've saved your default  WooCommerce Settings!", 'gamedev');
	$trans6 = __("Thanks for using GameDev, and", 'gamedev');
	$trans7 = __("be sure to check out the GameDev Extensions!", 'gamedev');
	$trans8 = __("If you happen to be thrilled with GameDev, then by all means,", 'gamedev');
	$trans9 = __("Feel Free to Leave a 5-Star Review Here!", 'gamedev');

	?>
  	<script type="text/javascript" >
  	"use strict";
  	jQuery(document).ready(function($) {
	  	$("#gamedev--woo-settings-button").click(function(event){

	  		$('#gamedev--woo-set-success-div').html('');
	  		$('.gamedev-spinner').animate({'opacity':'1'});

	  		var salePrice = $( "input[name='book-woo-sale-price']" ).val();
			var regularPrice = $( "input[name='book-woo-regular-price']" ).val();
			var stock = $( "input[name='book-woo-stock']" ).val();
			var length = $( "input[name='book-woo-length']" ).val();
			var width = $( "input[name='book-woo-width']" ).val();
			var height = $( "input[name='book-woo-height']" ).val();
			var weight = $( "input[name='book-woo-weight']" ).val();
			var sku = $("#gamedev-addbook-woo-sku" ).val();
			var virtual = $("input[name='gamedev-woocommerce-vert-yes']").prop('checked');
			var download = $("input[name='gamedev-woocommerce-download-yes']").prop('checked');
			var salebegin = $('#gamedev-addbook-woo-salebegin').val();
			var saleend = $('#gamedev-addbook-woo-saleend').val();
			var purchasenote = $('#gamedev-addbook-woo-note').val();
			var productcategory = $('#gamedev-woocommerce-category-select').val();
			var reviews = $('#gamedev-woocommerce-review-yes').prop('checked');
			var upsells = $('#select2-upsells').val();
			var crosssells = $('#select2-crosssells').val();

			var upsellString = '';
			var crosssellString = '';

			// Making checks to see if  extension is active
			if(upsells != undefined){
				for (var i = 0; i < upsells.length; i++) {
					upsellString = upsellString+','+upsells[i];
				};
			}

			if(crosssells != undefined){
				for (var i = 0; i < crosssells.length; i++) {
					crosssellString = crosssellString+','+crosssells[i];
				};
			}

			if(salebegin != undefined && saleend != undefined){
				// Flipping the sale date start
				if(salebegin.indexOf('-')){
					var finishedtemp = salebegin.split('-');
					salebegin = finishedtemp[0]+'-'+finishedtemp[1]+'-'+finishedtemp[2]
				}

				// Flipping the sale date end
				if(saleend.indexOf('-')){
					var finishedtemp = saleend.split('-');
					saleend = finishedtemp[0]+'-'+finishedtemp[1]+'-'+finishedtemp[2]
				}	
			}

		  	var data = {
				'action': 'gamedev__save_action_default',
				'security': '<?php echo wp_create_nonce( "gamedev__save_default_action_callback" ); ?>',
				'saleprice':salePrice,
				'regularprice':regularPrice,
				'stock':stock,
				'length':length,
				'width':width,
				'height':height,
				'weight':weight,
				'sku':sku,
				'virtual':virtual,
				'download':download,
				'salebegin':salebegin,
				'saleend':saleend,
				'purchasenote':purchasenote,
				'productcategory':productcategory,
				'reviews':reviews,
				'upsells':upsellString,
				'crosssells':crosssellString
			};
			console.log(data);

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {
			    	console.log(response);


			    	$('#gamedev--woo-set-success-div').html("<span id='gamedev-add-book-success-span'><?php echo $trans1 ?></span><br/><br/>&nbsp;<?php echo $trans2 ?><div id='gamedev-addtemplate-success-thanks'><?php echo $trans6 ?>&nbsp;<a href='http://gamedev.com/index.php/extensions/'><?php echo $trans7 ?></a><br/><br/>&nbsp;<?php echo $trans8 ?> &nbsp;<a id='gamedev-addbook-success-review-link' href='https://wordpress.org/support/plugin/gamedev/reviews/?filter=5'><?php echo $trans9 ?></a><img id='gamedev-smile-icon-1' src='http://evansclienttest.com/wp-content/plugins/gamedev/assets/img/icons/smile.png'></div>");

			    	$('.gamedev-spinner').animate({'opacity':'0'});

			    	$('html, body').animate({
				        scrollTop: $("#gamedev--woo-set-success-div").offset().top-100
				    }, 1000);
			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});

			event.preventDefault ? event.preventDefault() : event.returnValue = false;
	  	});
	});
	</script>
	<?php
}

// Callback function for creating backups
function gamedev__save_default_action_callback(){
	global $wpdb;
	check_ajax_referer( 'gamedev__save_default_action_callback', 'security' );
	$saleprice = filter_var($_POST['saleprice'],FILTER_SANITIZE_STRING);
	$regularprice = filter_var($_POST['regularprice'],FILTER_SANITIZE_STRING);
	$stock = filter_var($_POST['stock'],FILTER_SANITIZE_STRING);
	$length = filter_var($_POST['length'],FILTER_SANITIZE_STRING);
	$width = filter_var($_POST['width'],FILTER_SANITIZE_STRING);
	$height = filter_var($_POST['height'],FILTER_SANITIZE_STRING);
	$weight = filter_var($_POST['weight'],FILTER_SANITIZE_STRING);
	$sku = filter_var($_POST['sku'],FILTER_SANITIZE_STRING);
	$virtual = filter_var($_POST['virtual'],FILTER_SANITIZE_STRING);
	$download = filter_var($_POST['download'],FILTER_SANITIZE_STRING);
	$woofile = filter_var($_POST['woofile'],FILTER_SANITIZE_STRING);
	$salebegin = filter_var($_POST['salebegin'],FILTER_SANITIZE_STRING);
	$saleend = filter_var($_POST['saleend'],FILTER_SANITIZE_STRING);
	$purchasenote = filter_var($_POST['purchasenote'],FILTER_SANITIZE_STRING);
	$productcategory = filter_var($_POST['productcategory'],FILTER_SANITIZE_STRING);
	$reviews = filter_var($_POST['reviews'],FILTER_SANITIZE_STRING);
	$crosssells = filter_var($_POST['crosssells'],FILTER_SANITIZE_STRING);
	$upsells = filter_var($_POST['upsells'],FILTER_SANITIZE_STRING);


	$data = array(
		'defaultsaleprice' => $saleprice,
		'defaultprice' => $regularprice,
		'defaultstock' => $stock,
		'defaultlength' => $length,
		'defaultwidth' => $width,
		'defaultheight' => $height,
		'defaultweight' => $weight,
		'defaultsku' => $sku,
		'defaultvirtual' => $virtual,
		'defaultdownload' => $download,
		'defaultsalebegin' => $salebegin,
		'defaultsaleend' => $saleend,
		'defaultnote' => $purchasenote,
		'defaultcategory' => $productcategory,
		'defaultreviews' => $reviews,
		'defaultcrosssell' => $crosssells,
		'defaultupsell' => $upsells
	);

 	$table = $wpdb->prefix."gamedev_jre__options";
   	$format = array( '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'); 
    $where = array( 'ID' => 1 );
    $where_format = array( '%d' );
    $result = $wpdb->update( $table, $data, $where, $format, $where_format );

	echo $result;



	wp_die();
}


function gamedev__upcross_pop_action_javascript() { 
	?>
  	<script type="text/javascript" >
  	"use strict";
  	jQuery(document).ready(function($) {

		  	var data = {
				'action': 'gamedev__upcross_pop_action',
				'security': '<?php echo wp_create_nonce( "gamedev__upcross_pop_action_callback" ); ?>',
			};

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {
			    	response = response.split('–sep-seperator-sep–');
			    	var upsellstitles = '';
			    	var crosssellstitles = '';


			    	if(response[0] != 'null'){
				    	upsellstitles = response[0];
				    	if(upsellstitles.includes(',')){
				    		var upsellArray = upsellstitles.split(',');
				    	} else {
				    		var upsellArray = upsellstitles;
				    	}

				    	$("#select2-upsells").val(upsellArray).trigger('change');
			    	}

			    	if(response[1] != 'null'){
				    	crosssellstitles = response[1];
				    	if(crosssellstitles.includes(',')){
				    		var upsellArray = crosssellstitles.split(',');
				    	} else {
				    		var upsellArray = crosssellstitles;
				    	}

				    	$("#select2-crosssells").val(upsellArray).trigger('change');
			    	}


			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});


	});
	</script>
	<?php
}

// Callback function for creating backups
function gamedev__upcross_pop_action_callback(){
	global $wpdb;
	check_ajax_referer( 'gamedev__upcross_pop_action_callback', 'security' );
		
	// Get saved settings
    $settings_table = $wpdb->prefix."gamedev_jre__options";
    $settings = $wpdb->get_row("SELECT * FROM $settings_table");

    echo $settings->defaultupsell.'–sep-seperator-sep–'.$settings->defaultcrosssell;

	wp_die();
}

/*
// For adding a book from the admin dashboard
add_action( 'admin_footer', 'gamedev__action_javascript' );
add_action( 'wp_ajax_gamedev__action', 'gamedev__action_callback' );
add_action( 'wp_ajax_nopriv_gamedev__action', 'gamedev__action_callback' );


function gamedev__action_javascript() { 
	?>
  	<script type="text/javascript" >
  	"use strict";
  	jQuery(document).ready(function($) {
	  	$("#gamedev-admin-addbook-button").click(function(event){

		  	var data = {
				'action': 'gamedev__action',
				'security': '<?php echo wp_create_nonce( "gamedev__action_callback" ); ?>',
			};
			console.log(data);

	     	var request = $.ajax({
			    url: ajaxurl,
			    type: "POST",
			    data:data,
			    timeout: 0,
			    success: function(response) {
			    	console.log(response);
			    },
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(errorThrown);
		            console.log(textStatus);
		            console.log(jqXHR);
				}
			});

			event.preventDefault ? event.preventDefault() : event.returnValue = false;
	  	});
	});
	</script>
	<?php
}

// Callback function for creating backups
function gamedev__action_callback(){
	global $wpdb;
	check_ajax_referer( 'gamedev__action_callback', 'security' );
	//$var1 = filter_var($_POST['var'],FILTER_SANITIZE_STRING);
	//$var2 = filter_var($_POST['var'],FILTER_SANITIZE_NUMBER_INT);
	echo 'hi';
	wp_die();
}*/



