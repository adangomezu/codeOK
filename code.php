<?php 


/************** PORTFOLIO LISTING ***************/

function alc_portlisting($atts, $content=NULL){
    extract(shortcode_atts(array(
		"limit" => 6,
		"featured" => 0,
		'anim'=>'',
		'class'=>'',
		'auto'=>'false',
		'sitems' => '3',
		'singlepagelink' => '1',
		'type' => ''
		), $atts));
 	global $post;
	$return = '';
    $counter = 0; 
	$isActive = '';
	$args = array('post_type' => 'portfolio', 'taxonomy'=> 'portfolio_category', 'showposts' => $limit, 'posts_per_page' => $limit, 'orderby' => 'date','order' => 'DESC');
	
	if ($featured)
	{
		$args['meta_query'] = array(array('key'=>'_portfolio_featured'));
	}
	
   	$query = new WP_Query($args);
	$anim=isset($anim) ? 'animation '.$anim : '';
	
	$return.='<div class="carousel-box '.$class.' '.$anim.'" >';
	if($type==1){
		$return.='<div class="carousel" data-carousel-autoplay="'.$auto.'" data-carousel-items="'.$sitems.'" data-carousel-nav="true" data-carousel-pagination="false"  data-carousel-speed="1000">';
	}else{
		$return.='<div class="carousel carousel-simple" data-carousel-autoplay="false" data-carousel-items="'.$sitems.'" data-carousel-nav="false" data-carousel-pagination="true"  data-carousel-speed="1000">';
	}
				if ($query->have_posts()):  
						while ($query->have_posts()) : 							
						$query->the_post();
						$custom = get_post_custom($post->ID);
						$link = ''; 

						$thumbnail = get_the_post_thumbnail($post->ID, 'portfolio');
						if (!empty($thumbnail)){ $thumbnail=wp_get_attachment_url( get_post_thumbnail_id($post->ID, 'portfolio')); }else{ $thumbnail=get_template_directory_uri()'/images/img_default.jpg'; };


						$overlay=isset($custom["_portfolio_item_overlay"][0]) ? $custom["_portfolio_item_overlay"][0] : '';
						//if ($counter == 0 || $counter % 4 == 0): $return.='<div class="item'.$isActive.'">'; endif;
						if($type==1){
							$return.='<div class="carousel-item animation fadeInLeft">';
						}else{
							$return.='<div class="carousel-item animation fadeInUp">';
							}
								$return.='<div class="overlay-wrapper" style="background: url('.$thumbnail.');">';
											/*if (!empty($thumbnail)): 
												$return.=$thumbnail;
												
											endif;	*/
									$return.='<div class="overlay-wrapper-content">
												<div class="overlay-details">';
													if (!empty($custom['_portfolio_video'][0])) : $link = $custom['_portfolio_video'][0]; 
													$return.='<a href="'.$link .'" data-lightbox="video" class="color-white" title="'.get_the_title().'">
																<span class="livicon" data-n="video-play"  data-color="#ffffff" data-hovercolor="#ffffff" data-op="1" data-onparent="true"></span>
															</a>';
													elseif (isset($custom['_portfolio_link'][0]) && $custom['_portfolio_link'][0] != '') : 
													$return.='<a href="'.$custom['_portfolio_link'][0].'" class="color-white" title="'.get_the_title().'">
																<span class="livicon" data-n="external-link"  data-color="#ffffff" data-hovercolor="#ffffff" data-op="1" data-onparent="true"></span>
															</a>';
													elseif (isset($custom['_portfolio_no_lightbox'][0]) && $custom['_portfolio_no_lightbox'][0] != '') : $link = get_permalink(get_the_ID()); 
													$return.='<a href="'.$link.'" class="color-white" title="'.get_the_title().'">
																<span class="livicon" data-n="more"  data-color="#ffffff" data-hovercolor="#ffffff" data-op="1" data-onparent="true"></span>
															</a>';
													 else : 
															$full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full', false);
															$link = $full_image[0];
													$return.='<a data-lightbox="image" href="'.$link.'" class="color-white" title="'.get_the_title().'">
																<span class="livicon" data-n="plus"  data-color="#ffffff" data-hovercolor="#ffffff" data-op="1" data-onparent="true"></span>
															</a>';
													endif; 
													if ($singlepagelink == '1') : 
														if(!(isset($custom['_portfolio_no_lightbox'][0]) && $custom['_portfolio_no_lightbox'][0] != '')):
															$link = get_permalink(get_the_ID()); 
															$return.='<a href="'.$link.'" class="color-white" title="'.get_the_title().'">
																<span class="livicon" data-n="more"  data-color="#ffffff" data-hovercolor="#ffffff" data-op="1" data-onparent="true"></span>
															</a>';	
														endif; 
													endif;
									$return.='</div>
											  <div class="overlay-bg '.$overlay.'"></div>
											</div>
										</div>
										<div class="portfolio-item-content">
											<h3 class="portfolio-item-title">'.get_the_title().'</h3>';

											$my_excerpt = get_the_excerpt($post->ID);
											if ($my_excerpt!='') {	
												$return.='<p>'.limit_words(get_the_excerpt($post->ID), 8).'</p>';
											}

											
											$return.='<span class="cl-effect-6 animation   animation-active"><a href="'.$link.'" class="animation   animation-active">Ver más...</a></span>

										</div>	                                                
									</div>';
						//if ($counter >0 && ($counter+1) % 4 == 0): $return.='</div>'; endif;							
					$counter ++; endwhile; 
					endif;
					$return.='</div></div>';
	
	return $return;
}

add_shortcode('portlist', 'alc_portlisting');
/*************************************************/

?>