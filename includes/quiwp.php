<?php
	class dswp{
		public $taxdata='';
		public $posttypemeta='';
		public $taxonomy;

		public function __constructor(){

		}
/********************************START CREATING NEW CUSTOM PAGE*******************************/
	public function newPagetype($atts){
		extract($atts);
    	add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	}


/********************************END CREATING NEW CUSTOM PAGE*******************************/
/********************************CREATING NEW CUSTOM POST TYPE*******************************/
    	public function newPosttype($atts){
    		extract($atts); //posttype,name,puralname,icon,notsupported
    			$name=strtolower($name);
    			$singlelabel=ucfirst($name);
    			$puralname=ucfirst($puralname);
    			$icon=!empty($icon)?$icon:'';
    			//$parentmenu=!empty($parentmenu)?$parentmenu:false;
    			$array= array('title','editor','thumbnail');
    			$supports=$array;
    			if(!empty($notsupported) and is_array($notsupported)){
    				$supports = array_diff($array, $notsupported);
    			}


    			$labels = array(
					'name' => _x($puralname, 'post type general name'),
					'singular_name' => _x($singlelabel, 'post type singular name'),
					'add_new' => _x('Add  '.$singlelabel, $puralname),
					'add_new_item' => __('Add New '.$singlelabel),
					'edit_item' => __('Edit '.$singlelabel),
					'new_item' => __('New '.$singlelabel),
					'view_item' => __('View '.$singlelabel),
					'search_items' => __('Search '.$singlelabel),
					'not_found' =>  __('Nothing found'),
					'not_found_in_trash' => __('Nothing found in Trash'),
					'parent_item_colon' => ''
				);
			 	$capabilities = array(
					'publish_posts' => 'publish_'.$name,
					'edit_posts' => 'edit_'.$name,
					'edit_published_posts'=>' edit_published_'.$name,
					'edit_others_posts' => 'edit_others_'.$name,
					'edit_private_posts'=>'edit_private_'.$name,
					'delete_posts' => 'delete_'.$name,
					'delete_published_posts' => 'delete_published_'.$name,
					'delete_others_posts' => 'delete_others_'.$name,
					'delete_private_posts'=>'delete_private_'.$name,
					'read_private_posts' => 'read_private_'.$name,
			    );
				$args = array(
					'labels' => $labels,
					'public' => true,
					'publicly_queryable' => true,
					'show_ui' => true,
					'query_var' => true,
					'menu_icon' => $icon,
					'rewrite' => true,
					'capability_type' => 'post',
					'hierarchical' => false,
					'menu_position' => null,
					'supports' =>$supports,
					//'menu_slug'=>$parentmenu
				);
				// if($parentmenu){

				// 	$args['show_in_menu']=false;
				// 	//$args['parent_slug']=$parentmenu;
				// 	 array(
		  //               'parent_slug'   => $parent,
		  //               'page_title'    => 'Special Pricing',
		  //               'menu_title'    => 'Special Pricing',
		  //               'capability'    => 'read',
		  //               'menu_slug'     => 'edit.php?post_type=$posttype,
		  //               'function'      => null,// Doesn't need a callback function.
    //         		),

				// }
				register_post_type( $posttype , $args );

    	}//newposttype ends

/********************************CREATING NEW CUSTOM TAXONOMY*******************************/
    	public function newTaxonomy($atts){
    		//$atts: posttype,taxonomy,name,puralname,
    		extract($atts);
    			//$name=strtolower($name);
    			$singlename=ucwords($name);
    			$puralname=ucfirst($puralname);
    			$showmetabox=!empty($showmetabox)?$showmetabox:true;
    			$this->taxonomy=$taxonomy;
    			if(!$showmetabox){
    				//add_action( 'wp_head',  );
    			}

				$labels = array(
					'name'              			=> _x( $puralname, 'taxonomy general name' ),
					'singular_name'    				=> _x( $puralname, 'taxonomy singular name' ),
					'search_items'     				=> __( 'Search '.$puralname ),
					'all_items'         			=> __( 'All '.$puralname ),
					'parent_item'       			=> __( 'Parent '.$singlename ),
					'parent_item_colon' 			=> __( 'Parent '.$singlename.':' ),
					'edit_item'         			=> __( 'Edit '.$singlename ),
					'update_item'       			=> __( 'Update '.$singlename ),
					'add_new_item'     				=> __( 'Add New '.$singlename ),
					'new_item_name'    				=> __( 'New '.$singlename.' Name' ),
					'menu_name'         			=> __( $puralname ),
				);

				$args = array(
					'hierarchical'     		 	=> true,
					'labels'            		=> $labels,
					'show_ui'           		=> true,
					'show_admin_column' 		=> false,
					'show_in_nav_menus' 		=> true,
					'query_var'        			=> true,
    				'rewrite'           		=> array( 'slug' => "$taxonomy" ),
					//u'meta_box_cb'                => $showmetabox,
				);
				register_taxonomy($taxonomy, $posttype, $args );

		}//newtaxonomyends

/********************************ADDING  NEW CUSTOM FIELD FOR TAXONOMY *******************************/
		public function newTaxmeta($taxonomy, $atts){
			//$atts:array('taxonomy'=>'',array('name'=>'', label'=>'xyz','fieldtype'=>'text/select/textarea/email/image', 'value'=>'single/array'))
			//$args=$atts;
			$args=array();
			$postid=false;
			$extrafunction = 'extra_'.$taxonomy.'_fields';
			if(isset($_GET['tag_ID'])) {
				$t_id=$_GET['tag_ID'];
				$cat_meta = get_option($taxonomy."_$t_id");
			}else{
				$cat_meta=array();
			}


     		$html='';

     			foreach ($atts as $arr) {
     				extract($arr);

     				//print_r($cat_meta);
     				$html.='<div class="form-field">';
     				$html.="	<label>$label</label>";
     				$fieldtype=!empty($fieldtype)?$fieldtype:"text";
     				$value=!empty($value)?$value:"";
     				$value=!empty($cat_meta["$name"])?$cat_meta["$name"]:$value;

     				$required=!empty($required)?' required':'';
	     			$multiple=!empty($multiple)?' multiple':'';
	     			$id=!empty($id)? $id:'';

     				$args['name']=$name;
					$args['id']=$id;
					$args['value']=$value;
					$args['required']=$required;
					//$args['multiple']=$multiple;


					$html.=$this->ds_formfield($fieldtype, $args);
     			$html.='</div>';

     			}//end atts
     		$this->taxdata=$html;

			add_action ($taxonomy.'_edit_form_fields', function(){ echo $this->taxdata;}, 10, 2);
			add_action($taxonomy.'_add_form_fields',function(){ echo $this->taxdata;}, 10, 2);
			add_action('created_'.$taxonomy, array ($this,'ds_savetaxmeta'), 10, 2);
			add_action ('edited_'.$taxonomy, array ($this,'ds_savetaxmeta'), 10, 2);
		}

/********************************SAVING NEW CUSTOM FIELD FOR TAXONOMY*******************************/
		function ds_savetaxmeta($term_id){
			if ( isset( $_POST['Cat_meta'] ) ) {
			 	$taxonomy=$_POST['taxonomy'];
		        $t_id = $term_id;
		        //$t_id = $_POST['tag_ID'];
		        $cat_meta = get_option( "Cat_$t_id");
		        $cat_keys = array_keys($_POST['Cat_meta']);
		        foreach ($cat_keys as $key){
		            if (isset($_POST['Cat_meta'][$key])) $cat_meta[$key] = $_POST['Cat_meta'][$key];
		        }
		    	update_option( $taxonomy."_$t_id", $cat_meta );
    		}
		}
/***************************CREATING NEW CUSTOM FIELDS FOR CUSTOM POST TYPE**************************/
 		function ds_custompostmeta($atts){
			foreach($atts as $box=>$boxdata){
				extract($boxdata);
				$args=array();
				if(empty($posttype)) die("Posttype not defined");
				$position=isset($position)?$position:"low";
				$priority=isset($priority)?$priority:"normal";
				$this->posttypemeta=$fields;

				add_meta_box("ds_".$posttype."_block".$box, "$label", function(){
					$html='<div class="dswp_content_block">';
					foreach($this->posttypemeta as $label=>$fdata){
						extract($fdata);
						$html.='<div class="form-field">';
	     				$html.="	<label>$label :</label>";
	     				$fieldtype=!empty($fieldtype)?$fieldtype:"text";
	     				$required=!empty($required)?' required':'';
	     				$multiple=!empty($multiple)?' multiple':'';

						$id=!empty($id)?' id='.$id:'';
						$postid=false;
	     				if(isset($_GET['post']) and isset($_GET['action'])){
							$postid=$_GET['post'];
							$args['postid']=$postid;

							$value=get_post_meta($postid, "$name", true );
						}else{
							$value=!empty($value)?$value:'';
						}


						$args['name']=$name;
						$args['id']=$id;
						$args['value']=$value;
						$args['required']=$required;
						$args['multiple']=$multiple;
						//$args['fieldtype']=$fieldtype;


						$html.=$this->ds_formfield($fieldtype, $args);


	     				$html.='</div>';

					}
					$html.="</div>";
					echo $html;
				}, "$posttype", "$priority", "$position");

			}

			}//ends custompostmeta

/*************************SAVING NEW CUSTOM FIELDS FOR CUSTOM POST TYPE************************/
		function ds_formfield($fieldtype,$args){
			extract($args);
			$html='';
			$postid=false;
			$fetch_subitems=false;
			switch($fieldtype):
	     					case 'textarea':
	     						$html.="<textarea name=\"ds_pmeta[$name]\" $id $required>$value</textarea>";
	     					break;
	     					case 'select':
	     						$multiple=!empty($multiple)?' multiple':false;
								if($multiple){
		     						$html.="<select name=\"ds_pmeta[$name][]\" $id $required $multiple autocomplete=\"off\">";
								}else{
									$html.="<select name=\"ds_pmeta[$name]\" $id $required>";
								}
	     						$html.="<option value=\"\">Select Any</option>";
	     							foreach ($options as $k => $v) {
										if($multiple){
											$vals=explode(",",$value);
											$selected=(in_array($k,$vals))?'selected="selected"':"";

										}else{
	     									$selected=($k==$value)?'selected="selected"':"";
										}
	     								$html.="<option value=\"$k\" $selected>$v</option>";
	     							}
	     						$html.="</select>";
	     					break;
							case 'rte':
								$editor_id=$name;
								$args=is_array($args)?$args:array();
								ob_start();
								wp_editor( $value, $editor_id, $args );
								$html.=ob_get_contents();
								ob_clean();
							break;
	     					case 'image':
	     						$html.="<div class=\"ds_field_image\"><input type=\"url\" name=\"ds_pmeta[$name]\" id=\"".$name."_meta_image\" value=\"$value\" placeholder=\"Browse Image\" $required>
								<input type=\"button\" name=\"".$name."_meta_image_button\" id=\"".$name."_meta_image_button\" class=\"uk-button uk-button-primary uk-margin-small-top dswp_upload_button\" value=\"Upload\"> </div>";
	     					break;
	     					case 'taxonomy':
	     						$args['echo']=0;
	     						$args['name']="tax_input[".$args['taxonomy']."][]\" autocomplete=\"off";
								$selectedoptions=get_the_terms( $postid, $args['taxonomy'] );
								$args['selected']=$selectedoptions[0]->term_id;
	     						$html.=wp_dropdown_categories( $args );
								if($fetch_subitems and !empty($selectedoptions[0]->term_id)){
									$args['child_of']=$selectedoptions[0]->term_id;
									$args['selected']=$selectedoptions[1]->term_id;
									$args['name']=$fetch_subitems;
									$args['id']=$fetch_subitems;
									$html.="<span id=\"ds_subcats\">".wp_dropdown_categories( $args )."</span>";
								}
	     					break;
	     					case 'users':
	     						$args['echo']=0;
	     						$args['name']="ds_pmeta[$name]";
								$args['selected']=$value;
	     						$html.=wp_dropdown_users( $args );
	     					break;
							case 'map':
								if(isset($postid)){
									$ds_latitude = get_post_meta($postid, "ds_latitude", true );
		 							$ds_longitude = get_post_meta($postid, "ds_longitude", true );
		 						}else{
		 							$ds_latitude = '';
		 							$ds_longitude = '';
		 						}
								$html.="<div class=\"uk-grid\">
											<div class=\"uk-width-1-1\">
												<div id=\"map_canvas\">

												</div>
											</div>
											<div class=\"uk-width-small-1-2\">
												<dl>
													<dt>Latitude:</dt>
														<dd><input type=\"text\" name=\"ds_pmeta[ds_latitude]\" id=\"ds_latitude\"  value=\"<?php echo $ds_latitude;?>\"  /></dd>
											  </dl>
											</div>
											<div class=\"uk-width-small-1-2\">
												<dl>
													<dt>Longitude:</dt>
													 <dd><input type=\"text\" name=\"ds_pmeta[ds_longitude]\"  id=\"ds_longitude\" value=\"<?php echo $ds_longitude;?>\"  /></dd>
											  </dl>
											</div>";
								$html.="<script type=\"text/javascript\">";
								$html.="jQuery(document).ready(function(e) {";

									    if(!empty($ds_latitude) and !empty($ds_longitude)):
										$html.="mapLoad( $ds_latitude, $ds_longitude );";
										else:
										 $html.="mapLoad();";
										endif;
											
										$html.='});';
								$html.="</script>";
								break;
	     					default:
	     					$html.="<input type=\"$fieldtype\" name=\"ds_pmeta[$name]\" value=\"$value\" $id $required>";
	     				endswitch;
	     				return $html;
		}



    }//class ends
?>
