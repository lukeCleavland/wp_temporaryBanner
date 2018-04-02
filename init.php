<?php
/*
Plugin Name: Temporary Banner
Plugin URI: https://github.com/lukeCleavland/wp_temporaryBanner.git
Description: Schedule info to appear the home page.
Version: 1.0.0
Author: Luke Cleavland
*/
defined('ABSPATH') OR exit;
function tempbanner_markup($object)
{

  wp_nonce_field(basename(__FILE__), "tempbanner-nonce");

?>
    <div style="border: 1px dotted #ccc; padding:10px;">
        <label for="tempbanner"><strong>Timed Banner (temporary closing, etc.)</strong></label><br>
        <textarea name="tempbanner" rows="2" style="width:50%;"><?php echo get_post_meta($object->ID, "_tempbanner", true); ?></textarea>
       <div>
        <label for="tempstart"><strong>Start On Day</strong> </label><input name="tempstart" type="date" placeholder="First Day Shown" value="<?php echo get_post_meta($object->ID, "_tempstart", true); ?>"/>
       </div>
         <div>
        <label for="tempend"><strong>End On Day</strong> </label><input name="tempend" type="date" placeholder="End On Day" value="<?php echo get_post_meta($object->ID, "_tempend", true); ?>"/>
       </div>
         <hr>
       <label for="tempbanner2"><strong>Timed Banner 2 </strong></label><br>
        <textarea name="tempbanner2" rows="2" style="width:50%;"><?php echo get_post_meta($object->ID, "_tempbanner2", true); ?></textarea>
       <div>
        <label for="tempstart2"><strong>Start On Day</strong> </label><input name="tempstart2" type="date" placeholder="First Day Shown" value="<?php echo get_post_meta($object->ID, "_tempstart2", true); ?>"/>
       </div>
         <div>
        <label for="tempend2"><strong>End On Day</strong> </label><input name="tempend2" type="date" placeholder="End On Day" value="<?php echo get_post_meta($object->ID, "_tempend2", true); ?>"/>
       </div>
    </div>


            <?php
}

function save_tempbanner($post_id, $post, $update)
{
    if (!isset($_POST["tempbanner-nonce"]) || !wp_verify_nonce($_POST["tempbanner-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "page";
    if($slug != $post->post_type)
        return $post_id;

    $tempbanner_value = NULL;
    $tempstart_value = NULL;
    $tempend_value = NULL;
        $tempbanner2_value = NULL;
    $tempstart2_value = NULL;
    $tempend2_value = NULL;


    if(isset($_POST["tempbanner"]))
    {
        $tempbanner_value = $_POST["tempbanner"];
    }
    update_post_meta($post_id, "_tempbanner", $tempbanner_value);

    if(isset($_POST["tempstart"]))
    {
        $tempstart_value = $_POST["tempstart"];
    }
    update_post_meta($post_id, "_tempstart", $tempstart_value);

    if(isset($_POST["tempend"]))
    {
        $tempend_value = $_POST["tempend"];
    }
    update_post_meta($post_id, "_tempend", $tempend_value);


    if(isset($_POST["tempbanner2"]))
    {
        $tempbanner2_value = $_POST["tempbanner2"];
    }
    update_post_meta($post_id, "_tempbanner2", $tempbanner2_value);

    if(isset($_POST["tempstart2"]))
    {
        $tempstart2_value = $_POST["tempstart2"];
    }
    update_post_meta($post_id, "_tempstart2", $tempstart2_value);

    if(isset($_POST["tempend2"]))
    {
        $tempend2_value = $_POST["tempend2"];
    }
    update_post_meta($post_id, "_tempend2", $tempend2_value);

}

add_action("save_post", "save_tempbanner", 10, 3);

function add_tempbanner()
{
    global $post;
        $file = get_post_meta( $post->ID, '_wp_page_template', true );
      $filename = explode("/", $file);
     $filename = explode("-", $filename[1]);
    if($filename[0] == 'home'){
add_meta_box("tempbannertext", "Temp Banner", "tempbanner_markup", "page", "normal", "high", null);
    }

}

add_action("add_meta_boxes", "add_tempbanner");


function display_tempbanner(){

      $today = date("Y-m-d");
      $today = strtotime($today);
      $startonday = strtotime(get_post_meta(get_the_ID(),'_tempstart', true));
      $endonday = strtotime( get_post_meta(get_the_ID(),'_tempend', true));
      $content = get_post_meta(get_the_ID(),'_tempbanner', true);
      $startonday2 = strtotime(get_post_meta(get_the_ID(),'_tempstart2', true));
      $endonday2 = strtotime( get_post_meta(get_the_ID(),'_tempend2', true));
      $content2 = get_post_meta(get_the_ID(),'_tempbanner2', true);
      if (strlen($content)>0 && (($today >= $startonday) && (empty($endonday) || ($today < $endonday)))){
   $banner = '
      <div id="temp_banner" class="plain_text">'.$content.'</div>';
       }

   if (strlen($content2)>0 && (($today >= $startonday2) && (empty($endonday2) || ($today < $endonday2)))){
   $banner2 = '
      <div id="temp_banner" class="plain_text">'.$content2.'</div>';
       }
       return $banner.$banner2;
    }


?>