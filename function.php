// add the ajax fetch js
add_action( 'wp_footer', 'ajax_fetch' );
function ajax_fetch() {
?>
<script type="text/javascript">
function fetch(){

    jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'post',
        data: { action: 'data_fetch', keyword: jQuery('#keyword').val() },
        success: function(data) {
            jQuery('#datafetch').html( data );
        }
    });

}
</script>

<?php
}

// the ajax function
add_action('wp_ajax_data_fetch' , 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch','data_fetch');
function data_fetch(){

    $the_query = new WP_Query( 
      array( 
        'posts_per_page' => -1, 
        's' => esc_attr( $_POST['keyword'] ), 
        'post_type' => 'product' 
      ) 
    );
	$product = new WC_Product($the_query->post->ID);

    if( $the_query->have_posts() ) :
        while( $the_query->have_posts() ): $the_query->the_post(); global $product;

$myquery = esc_attr( $_POST['keyword'] );
$a = $myquery;
$search = get_the_title();
if( stripos("/{$search}/", $a) !== false) { ?>
             
		<div class="s-results">
			
			<div class="content">
			<h3><a href="<?php echo esc_url( post_permalink() ); ?>"><?php the_title();?></a></h3>
			<h4><?php echo wp_trim_words( get_the_content(), 14, '..'); ?></h4>
			<p><?php echo $product->get_price_html();?></p>
			</div>
			<div class="featuredimg">
				<?php
 	          woocommerce_show_product_sale_flash( $post, $product );
	          if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog');
	          else echo '<img src="'.woocommerce_placeholder_img_src().'" alt="Placeholder" />';
				?>
			</div>
		</div>

        <?php }
    endwhile;
        wp_reset_postdata();  
    endif;

    die();
}
