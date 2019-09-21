<?php
/*
Plugin Name: Tesimonial
Plugin URI: http://www.dhrusya.com
Description: Tesimonial.
Version: 1.0
Author: Venu Gopal Chaladi, Sushma Reddy
Author URI: http://www.dhrusya.com
Text Domain: tesimonial
*/

//ini_set( 'display_errors', true );
//error_reporting( E_ALL );

require_once ('includes/quiwp.php');

$dswp=new dswp();

add_action('init','ds_tesimonial');
function ds_tesimonial(){
	$dswp=new dswp();
	$atts=array('posttype'=>'testimonial','name'=>'Testimonial','puralname'=>'Testimonial','icon'=>'');
	$dswp->newPosttype($atts);
}

add_action('add_meta_boxes', 'ds_testimonial_postmeta');
    function ds_testimonial_postmeta(){
  		$dswp=new dswp();
        $atts=array(
                array(
                        'posttype'=>'testimonial',
                        'label'=>'Testimonial Data',
                        'fields'=>array(
										array(
                                            'label'=>'Person Name',
                                            'name'=>'testimonial_pname',
                                            'fieldtype'=>'text',
                                        ),
										 array(
                                            'label'=>'Website',
                                            'name'=>'testimonial_website',
                                            'fieldtype'=>'text',
                                        ),
                                        array(
                                            'label'=>'Designation',
                                            'name'=>'testimonial_designation',
                                            'fieldtype'=>'text',
                                        ),
                                        array(
                                            'label'=>'City',
                                            'name'=>'testimonial_city',
                                            'fieldtype'=>'text',
                                        )
                                    )
                    )
            );

        $dswp->ds_custompostmeta($atts);
    }

add_action('save_post', "ds_save_custompostmeta", 10,3);
function ds_save_custompostmeta($post_id, $post, $update){
            if(isset($_POST['ds_pmeta'])){
				$ds_pmeta=$_POST['ds_pmeta'];
				foreach($ds_pmeta as $k=>$v){
					update_post_meta($post_id, $k, $v);
				}
			}
}





function ds_team_output(){
	$type = 'testimonial';
	$args=array(
	  'post_type' => $type,
	  'post_status' => 'publish'
	);
$posts=query_posts($args);
//echo "<pre>";
//print_r($posts);
//echo "</pre>";
$postid = get_the_ID();
$meta = get_post_meta( get_the_ID() );

ob_start();
	$html="";
?>

<div class="ds_team_slider">
<div class="uk-slidenav-position" data-uk-slideshow="{animation: 'fade',autoplay:true}">
<ul class="uk-slideshow  uk-grid uk-grid-1-1 uk-margin-bottom">
<?php

if ( have_posts() ) : while (have_posts()) : the_post();

			 if ( has_post_thumbnail(get_the_id())) :
			$post_thumbnail_id = get_post_thumbnail_id(get_the_id(), 'full');
			$post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );
			$post_thumbnail_id1 = get_post_thumbnail_id(get_the_id(), 'thumbnail');
			$post_thumbnail_url1 = wp_get_attachment_url( $post_thumbnail_id1 );
			endif;
?>
<li>

		<!-- <div class="uk-grid"> -->
         		<div class="uk-text-center" style="color:#2E346C;">
         			<!-- <h1><?php the_title();?></h1> -->
                   	<h2 class=""><?php the_content(); ?></h2>
                </div>
         <!-- </div> -->

 </li>

<?php
  endwhile;
  endif;  ?>
  </ul>

    <!-- <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-previous" data-uk-slideshow-item="previous"></a>
    <a href="" class="uk-slidenav uk-slidenav-contrast uk-slidenav-next" data-uk-slideshow-item="next"></a>
     -->
    <ul class="uk-grid uk-grid-small uk-grid-width-1-3 uk-grid-width-medium-1-3 uk-flex-center ds_team_thumbnailblock uk-margin-large-top">
    <?php
		if ( have_posts() ) :
			$i=0;
		 while (have_posts()) : the_post();
	?>
        <li data-uk-slideshow-item="<?php echo $i;?>">
        	<div class="uk-grid ds_team_slider_person">
        		<div class="ds_image_person uk-width-small-1-1">
	            	<a href="#"><?php echo get_the_post_thumbnail(get_the_id(),'thumbnail');?></a>
	            </div>
	            <div class="ds_team_slider_person_info uk-width-medium-1-1" style="color:#2E346C;">
	            	<h4><?php echo get_post_meta(get_the_id(), 'testimonial_pname', true);?></h4>
                <p><?php echo get_post_meta(get_the_id(), 'testimonial_city', true);?></p>
	            </div>
	        </div>

        </li>
     <?php
	 $i++;
  endwhile;
  endif;  ?>
    </ul>

 </div>
</div>

    <?php
	$html=ob_get_contents();
	ob_clean();
	wp_reset_query();
	return $html;
}
add_shortcode("ds_team_output","ds_team_output");


add_action('wp_enqueue_scripts', 'testimonial_fe_scripts');
function testimonial_fe_scripts() {
    wp_enqueue_style( 'uikitcss', '//cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css');
    wp_enqueue_style('uikitslidenavcss', '//cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/components/slidenav.css');
    wp_enqueue_style('uikitslideshowcss', '//cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/components/slideshow.min.css');
    wp_enqueue_style('uikitslidercss', '//cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/components/slider.min.css');


    wp_register_script('uikitjs', "//cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/uikit.min.js", array('jquery'));
    wp_enqueue_script('uikitjs');
    wp_register_script('uikitslidesetjs', "//cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/components/slideset.min.js");
    wp_enqueue_script('uikitslidesetjs');
    wp_register_script('uikitslideshowjs', "//cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/components/slideshow.min.js");
    wp_enqueue_script('uikitslideshowjs');
    wp_register_script('uikitsliderjs', "//cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/components/slider.min.js");
    wp_enqueue_script('uikitsliderjs');
    wp_enqueue_style( 'quiwpcss', plugins_url('assets/css/quiwp.css', __FILE__));
    wp_enqueue_style( 'testimonialcss', plugins_url('assets/css/style_fe.css', __FILE__));
    wp_register_script('quiwpjs', plugins_url('assets/js/quiwp.js', __FILE__));
    wp_enqueue_script('quiwpjs');
    wp_register_script('testimonialjs', plugins_url('assets/js/style_fe.js', __FILE__));
    wp_enqueue_script('testimonialjs');
}
