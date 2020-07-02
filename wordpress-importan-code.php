// form with taxonomy and metaquery search with same page :--- https://leasetree.co.uk/specific-needs/vans/


function filter_van(){  
    if(is_page(21)){ ?>
        <form role="search" method="post" class="search-form" action="">    
                
        <?php 
        $term = get_term(95); //Example term ID
        $term->name; //gets term name
        ?>
        <input type="checkbox" id="<?php echo $term->term_id; ?>" value="<?php echo $term->slug; ?>" <?php if(isset($_POST['small-van'])){ echo 'checked="checked"'; } ?> name="<?php echo $term->slug; ?>" />
        <label for="<?php echo $term->slug; ?>"> <?php echo $term->name; ?></label> 
      
       <?php 
        $term = get_term(96); //Example term ID
        $term->name; //gets term name
        ?>
        <input type="checkbox" id="<?php echo $term->term_id; ?>" value="<?php echo $term->slug; ?>" <?php if(isset($_POST['medium-van'])){ echo 'checked="checked"'; } ?> name="<?php echo $term->slug; ?>" />
        <label for="<?php echo $term->slug; ?>"> <?php echo $term->name; ?></label> 
      
       <?php 
        $term = get_term(97); //Example term ID
        $term->name; //gets term name
        ?>
        <input type="checkbox" id="<?php echo $term->term_id; ?>" value="<?php echo $term->slug; ?>" <?php if(isset($_POST['large-van'])){ echo 'checked="checked"'; } ?> name="<?php echo $term->slug; ?>" />
        <label for="<?php echo $term->slug; ?>"> <?php echo $term->name; ?></label> 
        
        <?php 
        $term = get_term(94); //Example term ID
        $term->name; //gets term name
        ?>
        <input type="checkbox" id="<?php echo $term->term_id; ?>" value="<?php echo $term->slug; ?>" <?php if(isset($_POST['4-x-4'])){ echo 'checked="checked"'; } ?> name="<?php echo $term->slug; ?>" />
        <label for="<?php echo $term->slug; ?>"> <?php echo $term->name; ?></label> 
      
      <select name="manufacturer" id="newscat" >
        <option value="" <?php echo ($_POST['manufacturer'] == '') ? ' selected="selected"' : ''; ?>>Manufacturer</option>
        <?php 
            $categories = get_categories('taxonomy=manufacturer&post_type=vehicle'); 
            foreach ($categories as $category) : 
            echo '<option value="'.$category->slug.'"';
            echo ($_GET['manufacturer'] == ''.$category->name.'') ? ' selected="selected"' : '';
            echo '>'.$category->name.'</option>';
            endforeach; 
        ?>
        </select>
        <?php
        $vehicle_budget = "field_5efb29473c6d1";
        $budget_select = get_field_object($vehicle_budget);
        if( $budget_select ){
           echo '<select class="custom-select" id="fuel-type" name="' . $budget_select['key'] . '">';
           echo  '<option selected value="">Budget</option>';
               foreach( $budget_select['choices'] as $k => $v )
               {
                   echo '<option value="' . $k . '">' . $v . '</option>';
               }
           echo '</select>';
        }	
        ?>
        <input type="submit" name="submit_form" class="button" style="width: 100%;" value="<?php esc_attr_e( 'Search...', 'custom' ); ?>" />
    
    </form>
    <script type="text/javascript">
       $("#newscat").val("<?php echo $_POST['manufacturer'];?>");
       $("#fuel-type").val("<?php echo $_POST['field_5efb29473c6d1'];?>");
    </script>
<?php 
    }
}
add_shortcode('van_page_filter','filter_van');


//Van Page add All selected van post 
function van_page_post(){
    $query = new WP_Query( array( 
    'post_type' => 'vehicle' ,
    'post_status' => 'publish',
    'orderby' => 'title',
    'order' => 'ASC',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'type', // This is the taxonomy's slug!
            'field' => 'slug',
            'terms' => array('van') // This is the term's slug!
        )
    )
) );    

if(isset($_POST['submit_form'])){
    
    print_r($_POST);
    
    $small_van = $_POST['small-van'];
    $medium_van = $_POST['medium-van'];
    $large_van = $_POST['large-van'];
    $van_4x4 = $_POST['4-x-4'];
    $manufaturer = $_POST['manufacturer'];
    $budget  = $_POST['field_5efb29473c6d1'];
    if(!empty($budget)){
        $budget  = $_POST['field_5efb29473c6d1'];
    }else{
        $budget='';
    }
    echo $small_van ." ". $medium_van ." ". $large_van ." ". $van_4x4 ." ". $manufaturer ." ". $budget;
    


$carlistarg   =   array(

  'posts_per_page'   => -1,
  'post_type'     => 'vehicle',
  'post_status'     => 'publish',
  
     'meta_query' => array(
         'relation' => 'AND',
        	array(
              'key'       => 'vehicle_price_range',	//acf key
              'value'     =>  $budget,
              'compare'   => 'LIKE',
          )
        ),   
    'tax_query' => array(
        'relation' => 'OR',
        ),
      'orderby' => 'ID',
      'order' => 'ASC',
  );


  if(!empty($small_van)  || !empty($medium_van) || !empty($large_van)  || !empty($van_4x4)){

      $carlistarg['tax_query'][] = array(
    
            array(
                'taxonomy' => 'type',
                'field'    => 'slug',
                'terms'    => array( $small_van,$medium_van,$large_van,$van_4x4)
            ),
        );
 }
 
   if(!empty($manufaturer)){

      $carlistarg['tax_query'][] = array(
        
            array(
                'taxonomy' => 'manufacturer',
                'field'    => 'slug',
                'terms'    => array($manufaturer)
            ),            
        );
 }
// query
$getCarList = new WP_Query( $carlistarg );

// echo '<pre>';
// print_r($getCarList);
// echo '</pre>';
?>
<?php if( $getCarList->have_posts() ){ ?>
	<div class="section filter-div">
        <div class="container py-40">      
          <ul class="alm-reveal">
    	<?php while( $getCarList->have_posts() ) {
    	    
    	    $getCarList->the_post();
    	    //echo get_the_title().'<br>';
    	?>
    		<li>
                  <a href="<?php the_permalink(); ?>">                          
                    <?php  the_post_thumbnail(); ?>            
                    </a>
                   <h3>
                    <a href="<?php the_permalink(); ?>">           
                        <?php the_title(); ?>           
                    </a>
                    </h3> 
                </li> 
        
         <?php   
    	}
         wp_reset_query();
         wp_reset_postdata();
         ?>
         </ul>
      </div>
    </div>  

<?php }else{ 
        echo '<h3>No Posts Found</h3>';
    } 

}
else{
    echo "show-all-car";
}

?>             
<div class="section">
    <div class="container py-40">      
      <ul class="alm-reveal">
        <?php
        if ( $query->have_posts() ) : 
             while ( $query->have_posts() ) : $query->the_post(); 
             ?>          
               <li>
                  <a href="<?php the_permalink(); ?>">                          
                    <?php  the_post_thumbnail(); ?>            
                    </a>
                   <h3>
                    <a href="<?php the_permalink(); ?>">           
                        <?php the_title(); ?>           
                    </a>
                    </h3> 
                </li>                
            <?php 
            endwhile; 
            
              wp_reset_query();  
              wp_reset_postdata(); 
        
         endif; 
         ?>
      </ul>
    </div>
</div>  
    <?php
}
add_shortcode('van_page','van_page_post');