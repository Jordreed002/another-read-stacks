<?php
    /**
        * Plugin Name: Another Read Stacks
        * Description: Include your Stacks from your Another Read account and automatically generate a post for each Stack using this plugin.
        * Version: 1.0
        * Author: Another Read
        * Author URI: https://anotherread.com/
    */

    defined('ABSPATH') or die('You can/t access this');
    include_once("admin-page.php");
    include_once("another-read-cpt.php");
    include_once("another-read-post-creation.php");
    include_once("another-read-api.php");


    class AnotherReadStacks{

        function __construct()
        {

            if(isset($_POST['ar_login'])){
                $this->login();
            }
            if(isset($_POST['ar_logout'])){
                $this->logout();
            }

            //Adds scripts and styles
            add_action('admin_enqueue_scripts', array($this, 'add_admin_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'add_scripts'));

            //Initialises the custom post type
            add_action('init', array('AnotherReadStacksCPT', 'stacksCPT'));
            
            //Adds the meta boxes to the custom post type
            add_action('add_meta_boxes', array('AnotherReadStacksCPT', 'createMetaBoxes'));
            
            //Adds saving to meta boxes
            add_action('save_post', array('AnotherReadStacksCPT','saveStacksMetaBoxes'));
            
            //Adds the admin page
            add_action('admin_menu', array($this, 'adminMenu'));

            //Set template for CPT
            add_filter('single_template', array('AnotherReadStacksCPT', 'setTemplate'));
            
            if(isset($_POST['update_settings_stacks'])){
                $this->insertData();
            }
            elseif(isset($_POST['update_posts_stacks'])){
                $this->insertData();
                $this->createPosts();

                //print_r("things are working");
            }

            //Create RSS Feed for activity posts
            //add_action('init', array($this, 'another_read_rss_feed'));
            
        }



        // To run on plugin activation
        function activate(){
            AnotherReadStacksCPT::stacksCPT();
            flush_rewrite_rules();
        }

        // To run on deactivation
        function deactivate(){

            flush_rewrite_rules();
        }

        //Add menu page to wp-admin
        static function adminMenu(){

            global $menu;
            $menuExits = false;
            foreach($menu as $item){
                if($item[0] == 'Another Read'){
                    $menuExits = true;
                }
            }
            if($menuExits){
                
                add_submenu_page('AnotherRead', 'Stack settings', 'Stacks settings', 'manage_options', 'Stacks', array('AnotherReadAdmin','adminPage'));
            }
            elseif(!$menuExits){
                echo "menu exists";
                
                add_menu_page('Another Read settings', 'Another Read', 'manage_options', 'AnotherRead', array('AnotherReadAdmin','adminPage'), plugins_url('another-read-stacks/img/brand--red--small.svg'));
                add_submenu_page('AnotherRead', 'Stack settings', 'Stacks settings', 'manage_options', 'AnotherRead', array('AnotherReadAdmin','adminPage'));

            }

        }

        //Add css to admin pages
        function add_admin_scripts(){
            wp_enqueue_style('another-read-admin', plugin_dir_url(__FILE__) . 'another-read-admin.css', array(), '1.0.0', 'all');
        }

        //Add css to regular pages
        function add_scripts(){
            wp_enqueue_style('another-read-stacks', plugin_dir_url(__FILE__) . 'another-read-stacks.css', array(), '1.0.0', 'all');
        }

        //Creats posts
        function createPosts(){
            add_action('init', array('AnotherReadStacksPostCreator', 'create'));
        }

        //Login to another read
        function login(){
            $data = array(
                'username' => $_POST['username'],
                'password' => $_POST['password']
            );
            $loginPayload = AnotherReadApi::APIcall("login", $data);
            if($loginPayload['ApiCallWasSuccessful']){
                $newData = get_option('another_read_stacks_settings');
                $newData['usertoken'] = $loginPayload['Payload']['ApiKey'];
                $newData['usertokenExpiry'] = $loginPayload['Payload']['ApiKeyExpiryDate'];
                $newData['logged_in'] = true;
                update_option('another_read_stacks_settings', $newData);
            }
        }

        function logout(){
            $newData = get_option('another_read_stacks_settings');
            $newData['usertoken'] = '';
            $newData['usertokenExpiry'] = '';
            $newData['logged_in'] = false;
            update_option('another_read_stacks_settings', $newData);
        }

        function insertData(){

            $another_read_settings = array(
                'logged_in' => false,
                'usertoken' => '',
                'apiKey' => '',
                'apiCallSuccessful' => null,
                'keynote' => '0',
                'timestamp' => '0'
            );

            if(get_option('another_read_stacks_settings') !== false) {
                $another_read_settings = get_option('another_read_stacks_settings');
                $another_read_settings['apiKey'] = $_POST['apiKey'];
                $another_read_settings['keynote'] = $_POST['keynote'];
                update_option('another_read_stacks_settings', $another_read_settings);
            }
            else{
                add_option('another_read_stacks_settings', $another_read_settings);
    
                $this->insertData();
            }
        }
    }

    if( class_exists('AnotherReadStacks')){
        $AnotherReadStacks = new AnotherReadStacks();
    }
    // if(! wp_next_scheduled('getActivityPosts')){
    //     wp_schedule_event(time(), 'daily', 'getActivityPosts');
    // }


    //Activate hook
    register_activation_hook(__FILE__, array($AnotherReadStacks, 'activate'));

    //Deactivate hook
    register_deactivation_hook(__FILE__, array($AnotherReadStacks, 'deactivate'));



?>