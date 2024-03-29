<?php
/*
    Plugin Name: Custom Data Query
    Description: You can create data lists in the admin panel with CDQ. Users can query these data with the search form.
    Author: meteturkdonmez
    Author URI: http://metehanturkdonmez.com.tr
    Version: 1.0.0

    
    Custom Data Query is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    any later version.
     
    Custom Data Query is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.
     
    You should have received a copy of the GNU General Public License
    along with Custom Data Query. If not, see https://www.gnu.org/licenses/old-licenses/gpl-2.0.html.
*/



    if(!function_exists('wp_get_current_user')) {
      include(ABSPATH . "wp-includes/pluggable.php"); 
   }

   $user = wp_get_current_user();
   $allowed_roles = array('editor', 'administrator');


   global $cdq_db_version;
   global $cdq_url;
   global $cdq_notices;
   $cdq_db_version = '1.0';
   $cdq_url = '';

   class cdqPlug {
      protected $url;
      protected $path;
      protected $basename;

      function __construct() {
         add_action( 'plugins_loaded', array( $this, 'plugin_setup' ));
      }

      function plugin_setup() {

         global $wpdb;
         global $cdq_db_version;

         $this->url = plugin_dir_url(__FILE__);
         $this->path =  plugin_dir_path(__FILE__);
         $this->basename = plugin_basename(__FILE__);

         $list_table_name = $wpdb->prefix . 'cdq_data_lists';

         $charset_collate = $wpdb->get_charset_collate();

         $create_data_list_sql = "CREATE TABLE $list_table_name (
         id mediumint(9) NOT NULL AUTO_INCREMENT,
         name varchar(500) NOT NULL,
         button_text varchar(500) NOT NULL,
         placeholder_text varchar(500) NOT NULL,
         titles varchar(3000) NULL,
         create_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
         PRIMARY KEY  (id))
         $charset_collate;";

         require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

         dbDelta( $create_data_list_sql );


         $data_table_name = $wpdb->prefix . 'cdq_datas';

         $create_datas_sql = "CREATE TABLE $data_table_name (
         id mediumint(9) NOT NULL AUTO_INCREMENT,
         list_id mediumint(9) NOT NULL,
         datas varchar(3000) NULL,
         create_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
         PRIMARY KEY  (id))
         $charset_collate;";

         dbDelta( $create_datas_sql );

         add_option( 'cdq_db_version', $cdq_db_version );

      }


      function create_list(){

         global $wpdb;

         if (isset($_POST['data_list_title'])) {

            $data_list_title  = $_POST['data_list_title'];

            $front_search_button_text = $_POST['front_search_button_text'];

            $front_search_placeholder_text = $_POST['front_search_placeholder_text'];

            $data_name = $_POST['data-name'];

            $data_type = $_POST['data-type'];

            $data_search = $_POST['data-search'];

            $data_list_items = array();

            $cdq_data_list_insert_titles_error = 0;

            foreach ($data_name  as $key => $value) {

               if (empty($value) or empty($data_type[$key])) {
                  $cdq_data_list_insert_titles_error++;
               }

               $data_list_items[] = array('name' => $value, 'type' => $data_type[$key], 'search' => $data_search[$key]  );

            }

            $data_list_items_json = json_encode($data_list_items);

            if (!empty($data_list_title) and $cdq_data_list_insert_titles_error == 0) {
             $cdq_data_list_insert = $wpdb->insert($wpdb->prefix.'cdq_data_lists', array(
                'name' => $data_list_title,
                'button_text' => $front_search_button_text,
                'placeholder_text' => $front_search_placeholder_text,
                'titles' => $data_list_items_json
             ));

             if (!$cdq_data_list_insert) {
                echo $wpdb->print_error();
             }else{

                $cdq_manage_page_url=admin_url('/admin.php?page=cdq-manage-page');
                header('Location:'.$cdq_manage_page_url);

                exit;

             }
          }
       }
    }

    function edit_list(){

       global $wpdb;

       if (isset($_POST['data_list_title_edit'])) {

          $data_list_title  = $_POST['data_list_title_edit'];

          $front_search_button_text = $_POST['front_search_button_text'];

          $front_search_placeholder_text = $_POST['front_search_placeholder_text'];

          $data_name = $_POST['data-name'];

          $data_type = $_POST['data-type'];

          $data_search = $_POST['data-search'];

          $data_list_id = $_POST['data_list_id'];


          $data_list_items = array();

          $cdq_data_list_insert_titles_error = 0;

          foreach ($data_name  as $key => $value) {

             if (empty($value) or empty($data_type[$key])) {
                $cdq_data_list_insert_titles_error++;
             }

             $data_list_items[] = array('name' => $value, 'type' => $data_type[$key], 'search' => $data_search[$key] );

          }

          $data_list_items_json = json_encode($data_list_items);

          if (!empty($data_list_title) and $cdq_data_list_insert_titles_error == 0) {
           $cdq_data_list_insert = $wpdb->update($wpdb->prefix.'cdq_data_lists', array(
              'name' => $data_list_title,
              'button_text' => $front_search_button_text,
              'placeholder_text' => $front_search_placeholder_text,
              'titles' => $data_list_items_json
           ), array('id'=>$data_list_id));

           if (!$cdq_data_list_insert) {
              echo $wpdb->print_error();
           }else{

              $cdq_manage_page_url=admin_url('/admin.php?page=edit-data-list&cdq_list_id='.$data_list_id);
              header('Location:'.$cdq_manage_page_url);

              exit;

           }
        }
     }
  }

  function get_list(){

    global $wpdb;

    $table_name = $wpdb->prefix . 'cdq_data_lists';

    $field_name = '*';

    return $wpdb->get_results( "SELECT {$field_name} FROM {$table_name}" );

 }

 function get_list_item($id){

    global $wpdb;

    $table_name = $wpdb->prefix . 'cdq_data_lists';

    $field_name = '*';

    return $wpdb->get_results( "SELECT {$field_name} FROM {$table_name} WHERE id='$id' " );

 }

 function delete_list(){

    global $wpdb;

    global $cdq_notices;

    if (isset($_GET['cdq_delete_list_ids']) and !empty($_GET['cdq_delete_list_ids'])) {

       $cdq_delete_list_ids = json_decode($_GET['cdq_delete_list_ids']);

       foreach ($cdq_delete_list_ids as $key => $value) {

          $table_name = $wpdb->prefix . 'cdq_data_lists';

          $delete = $wpdb->delete( $table_name, array( 'id' => $value ) );

          if ($delete) {

             $cdq_notices = "Deleted list.";

             if (count($cdq_delete_list_ids)>1) {
                $cdq_notices = "Deleted lists.";
             }
          }

       }

    }

    if (isset($_GET['cdq_delete_list_id']) and !empty($_GET['cdq_delete_list_id'])) {

     $table_name = $wpdb->prefix . 'cdq_data_lists';

     $delete = $wpdb->delete( $table_name, array( 'id' => $_GET['cdq_delete_list_id'] ) );

     if ($delete) {

        $cdq_notices = "Deleted list.";

     }

  }

}


function add_data(){

 global $wpdb;

 global $cdq_notices;

 if (isset($_POST['cdq_insert_field']) and !empty($_GET['cdq_list_id']) and $_POST['datas_id'] == 0) {

    $cdq_insert_field_json = json_encode($_POST['cdq_insert_field']);
    $cdq_list_id = $_GET['cdq_list_id'];

    $cdq_datas_insert = $wpdb->insert($wpdb->prefix.'cdq_datas', array(
       'list_id' => $cdq_list_id,
       'datas' => $cdq_insert_field_json
    ));

    if (!$cdq_datas_insert) {
       echo $wpdb->print_error();
    }else{

       $cdq_notices = "Data added.";

    }
 }


 if (isset($_POST['cdq_insert_field']) and !empty($_GET['cdq_list_id']) and !empty($_POST['datas_id']) and $_POST['datas_id'] != 0) {

  $cdq_insert_field_json = json_encode($_POST['cdq_insert_field']);
  $cdq_list_id = $_GET['cdq_list_id'];

  $cdq_datas_insert = $wpdb->update($wpdb->prefix.'cdq_datas', array(
     'list_id' => $cdq_list_id,
     'datas' => $cdq_insert_field_json
  ),array('id'=>$_POST['datas_id']));

  if (!$cdq_datas_insert) {
     echo $wpdb->print_error();
  }else{

     $cdq_notices = "Data updated.";

  }
}

}


function get_datas(){

 global $wpdb;

 $table_name = $wpdb->prefix . 'cdq_datas';

 $field_name = '*';

 return $wpdb->get_results( "SELECT {$field_name} FROM {$table_name}" );

}

function get_data($id){

 global $wpdb;

 $table_name = $wpdb->prefix . 'cdq_datas';

 $field_name = '*';

 return $wpdb->get_results( "SELECT {$field_name} FROM {$table_name} WHERE list_id = '$id' ");

}


function delete_data(){

 global $wpdb;

 global $cdq_notices;

 if (isset($_GET['delete_datas']) and !empty($_GET['delete_datas'])) {

    $delete_datas = json_decode($_GET['delete_datas']);

    foreach ($delete_datas as $key => $value) {

       $table_name = $wpdb->prefix . 'cdq_datas';
       $delete = $wpdb->delete( $table_name, array( 'id' =>  $value ) );

       if ($delete) {

          $cdq_notices = "Deleted data.";

          if (count($delete_datas)>1) {
             $cdq_notices = "Deleted datas.";
          }

       }

    }



 }

 if (isset($_GET['delete_data']) and !empty($_GET['delete_data'])) {

  $table_name = $wpdb->prefix . 'cdq_datas';
  $delete = $wpdb->delete( $table_name, array( 'id' => $_GET['delete_data'] ) );

  if ($delete) {
     $cdq_notices = "Deleted data.";
  }

}

}



function slugify($str, $options = array()) {
   
   $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
   
   $defaults = array(
      'delimiter' => '_',
      'limit' => null,
      'lowercase' => true,
      'replacements' => array(),
      'transliterate' => true,
   );
   
   
   $options = array_merge($defaults, $options);
   
   $char_map = array(
      // Latin
      'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
      'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
      'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
      'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
      'ß' => 'ss', 
      'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
      'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
      'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
      'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
      'ÿ' => 'y',

      // Latin symbols
      '©' => '(c)',

      // Greek
      'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
      'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
      'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
      'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
      'Ϋ' => 'Y',
      'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
      'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
      'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
      'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
      'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

      // Turkish
      'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
      'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 

      // Russian
      'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
      'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
      'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
      'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
      'Я' => 'Ya',
      'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
      'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
      'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
      'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
      'я' => 'ya',

      // Ukrainian
      'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
      'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

      // Czech
      'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
      'Ž' => 'Z', 
      'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
      'ž' => 'z', 

      // Polish
      'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
      'Ż' => 'Z', 
      'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
      'ż' => 'z',

      // Latvian
      'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
      'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
      'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
      'š' => 's', 'ū' => 'u', 'ž' => 'z'
   );
   
   // Make custom replacements
   $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
   
   // Transliterate characters to ASCII
   if ($options['transliterate']) {
      $str = str_replace(array_keys($char_map), $char_map, $str);
   }
   
   // Replace non-alphanumeric characters with our delimiter
   $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
   
   // Remove duplicate delimiters
   $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
   
   // Truncate slug to max. characters
   $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
   
   // Remove delimiter from ends
   $str = trim($str, $options['delimiter']);
   
   return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}




function cdq_find_query($text,$list_id){

 $list_info = $this->get_list_item($list_id);

 $list_titles = json_decode($list_info[0]->titles);

 $result_list = array();

 foreach ($list_titles as $key => $value) {

    if ($value->search == 'on') {

       global $wpdb;

       $table_name = $wpdb->prefix . 'cdq_datas';

       $field_name = '*';

       $jskey = '$.'.$this->slugify($value->name);

       $result = $wpdb->get_results( "SELECT {$field_name}, json_extract(datas, '$jskey') AS titlesname FROM {$table_name} WHERE list_id='$list_id' and LOWER(json_extract(datas, '$jskey')) like LOWER('%$text%') " );

       foreach ($result as $reskey => $resvalue) {

          if (isset($resvalue)) {

             $result_list[$resvalue->id] = json_decode($resvalue->datas);
          }
       }


    }

 }

 return $result_list;             

}


function cdq_front_list($result_list,$list_id){

 $titles_query = $this->get_list_item($list_id);

 $titles = json_decode($titles_query[0]->titles);

 echo "<div id='cdq_result_list'>";

 foreach ($result_list as $key => $item) {

    echo "<table>";

    foreach ($titles as $key => $title) {

       echo "<tr>";

       echo "<td>".$title->name."</td>";

       echo "<td>";

       if ($title->type == 'text') {

          echo $item->{$this->slugify($title->name)};

       }else{

          $attachment_url = wp_get_attachment_url($item->{$this->slugify($title->name)});

          if (!empty($attachment_url)) :
             echo "<a target='blank' class='front-preview-button' href='".$attachment_url."'><span class='dashicons dashicons-visibility'></span></a>";

          endif;

       }

       echo "</td>";

       echo "<tr>";
    }

    echo "</table>";

 }

 echo "</div>";

}

}

global $cdqplug;
$cdqplug = new cdqPlug();

$cdqplug->create_list();
$cdqplug->edit_list();
$cdqplug->delete_list();
$cdqplug->delete_data();

if( array_intersect($allowed_roles, $user->roles ) ) :

   add_action('admin_menu', 'cdq_create_menu_entry');

endif;

function cdq_create_menu_entry() {

   $icon = plugins_url('/images/cdq-icon-20.png', __FILE__);

   add_menu_page('CDQ Manage', 'CDQ Manage', 'edit_posts', 'cdq-manage-page', 'cdq_show_main_page', $icon);

   add_submenu_page( 'cdq-manage-page', esc_html__( 'Add New' , 'cdq'), esc_html__( 'Add New' , 'cdq'), 'edit_posts', 'new-data-list', 'cdq_add_page' );

   if ((isset($_GET['page'])) && ($_GET['page'] === 'edit-data-list') && !empty($_GET['cdq_list_id']) ) {

      add_submenu_page( 'cdq-manage-page', 'Edit Data List Info','Edit Data', 'edit_posts', 'edit-data-list', 'cdq_edit_page' );

   }

   if (isset($_GET['page']) && ($_GET['page'] === 'edit-data-list') && empty($_GET['cdq_list_id'])) {

      $cdq_manage_page_url=admin_url('/admin.php?page=cdq-manage-page');

      header('Location:'.$cdq_manage_page_url);

      exit;

   }

   if (isset($_GET['page']) && ($_GET['page'] === 'cdq-datas-page') && !empty($_GET['cdq_list_id']) ) {

      add_submenu_page( 'cdq-manage-page', 'Data List','Data List', 'edit_posts', 'cdq-datas-page', 'cdq_datas_page' );

   }

   if (isset($_GET['page']) && ($_GET['page'] === 'cdq-datas-page') && empty($_GET['cdq_list_id'])) {

      $cdq_manage_page_url=admin_url('/admin.php?page=cdq-manage-page');

      header('Location:'.$cdq_manage_page_url);

      exit;

   }

}

function cdq_show_main_page() {

   include('cdq-manage-page.php');

}

function cdq_add_page() {

   include('new-page-cdq.php');

}

function cdq_edit_page() {

   include('edit-page-cdq.php');

}

function cdq_datas_page(){

   include('cdq-datas-page.php');

}

function load_wp_media_files() {

   wp_enqueue_media();

}

add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );

function cdq_front_function($atts = []){

   if (!empty($atts)) {

      ob_start();

      wp_enqueue_style('cdq_front_style', plugins_url( '/css/front_style.css', __FILE__ ));

      extract(shortcode_atts(array('id' => 1,), $atts));

      $list_id = $atts['id'];

      global $cdqplug;

      $cdqplug = new cdqPlug();

      $list_item = $cdqplug->get_list_item($list_id);

      if (count($list_item) > 0) {

         ?>

         <div class="cdq_search_form">

            <form>

               <input type="text" name="cdq_search_text" value="<?php if(isset($_GET['cdq_search_text']) and !empty($_GET['cdq_search_text'])){ echo $_GET['cdq_search_text'];} ?>" placeholder="<?php echo $list_item[0]->placeholder_text; ?>">
               <button><?php echo $list_item[0]->button_text; ?></button>

            </form>

         </div>

         <?php

         if (isset($_GET['cdq_search_text']) and !empty($_GET['cdq_search_text'])) {
          $result_list = $cdqplug->cdq_find_query($_GET['cdq_search_text'],$list_id);

          if (count($result_list)>0) {
             $cdqplug->cdq_front_list($result_list,$list_id);                   
          }
          else{
             echo "<span class='cdq_no_result'><span class='dashicons dashicons-dismiss'></span></span>";
          }


       }

    }else{
     echo "Not found data list!";
  }

  $content = ob_get_contents();

  ob_end_clean();

  return $content;
}

}

function cdq_front_shortcodes(){

   add_shortcode('cdq-query-form', 'cdq_front_function');

}

add_action( 'init', 'cdq_front_shortcodes');


