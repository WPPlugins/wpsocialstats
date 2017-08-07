<?php

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Social_Stats_Table extends WP_List_Table {
    
    private $options;

    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'entry',     
            'plural'    => 'entries',    
            'ajax'      => false       
        ) );
        
    }

    function get_options(){

        return $this->options;
    }
    
    
    function column_default($item, $column_name){

        return $item[$column_name];
    }
    
    function column_title($item){
    
        //Return the title contents
        return "<a href='".esc_attr($item["permalink"])."' target='_blank'>".$item['title']."</a>";
    }
    
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }
    
    
    function get_columns(){
        $columns = array(
            /*'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text */
            'title'     => 'Title',
            'count_twitter'    => 'Twitter',
            'count_facebook'  => 'Facebook',
            'count_google'  => 'Google&nbsp;+',
            'count_stumbleupon'  => 'StumbleUpon',
            'count_linkedin'  => 'LinkedIn',
            'count_pinterest'  => 'Pinterest',
            'total'  => 'Total'
        );
        return $columns;
    }
    
    function get_sortable_columns( $is_sortable ) {

        if( $is_sortable ){

            $sortable_columns = array(
                'title'     => array('title',false),
                'count_twitter'     => array('twitter',false), 
                'count_facebook'     => array('facebook',false), 
                'count_google'     => array('google',false), 
                'count_stumbleupon'     => array('stumbleupon',false), 
                'count_linkedin'     => array('linkedin',false), 
                'count_pinterest'     => array('pinterest',false), 
                'total'     => array('total',false)
            );

        }
        else {

            $sortable_columns = array(
                'title'     => array('title',false)
            );
        }

        return $sortable_columns;
    }
    
    function get_bulk_actions() {
        $actions = array();
        return $actions;
    }
    
    function prepare_items() {

        $post_type = isset( $_GET['post_type'] ) ? (string)$_GET['post_type'] : "post";
        $per_page = isset( $_GET['per_page'] ) ? (int)$_GET['per_page'] : 10;
        $cat_perm = isset( $_GET['cat'] ) ? $_GET['cat'] : "-1";
        $show_date = isset( $_GET['date'] ) ? (int)$_GET['date'] : 0;
        $page = isset($_GET['paged'])?$_GET['paged']:1;
        $orderby = isset($_GET['orderby'])?$_GET['orderby']:"date";
        $order = isset($_GET['order'])?$_GET['order']:"asc";

        $date = $show_date;

        if( strlen($date) == 6 ){
            $month = substr($date,4,6);
            $year = substr($date,0,4);
        }
        else {
            $year = "";
            $month = "";
        }

        if( -1 != $cat_perm ){

            $category = $cat_perm;

        }else{

            $category = '';
        } 


        $recentPosts = new WP_Query();

        $allPosts = new WP_Query();

        if( 'page' == $post_type ){
            $allPosts->query(  'post_status=publish&showposts=-1&posts_per_page=-1&year='.$year.'&monthnum='.$month.'&post_type=page&cat='.$category );
        }
        else{
            $allPosts->query(  'post_status=publish&showposts=-1&posts_per_page=-1&year='.$year.'&monthnum='.$month.'&cat='.$category );
        }

        $old_wss_data = array();

        $all_wss_data = array();

        $start =true;

        while ( $allPosts->have_posts() ) : 

            $allPosts->the_post();

            $all_wss_data[] = get_the_ID();

            //update_post_meta( get_the_ID(), "WSS_DATA", null); 
            //update_post_meta( get_the_ID(), "WSS_UPDATED", null);

            $count_data = unserialize( get_post_meta( get_the_ID(), "WSS_DATA", true) );

            $start = false;

            if( $count_data === FALSE ){
                $old_wss_data[] =  get_the_ID();
            }

        endwhile;

        $is_sortable = count( $old_wss_data ) === 0;

        $sortable_query = "";

        if( $is_sortable || $orderby == "title" ){


            switch( $orderby ){

                case "facebook" :

                    $sortable_query .= "&orderby=meta_value_num&order=".$order."&meta_key=";

                    $sortable_query .= "WSS_DATA_FACEBOOK";

                break;

                case "google" :

                    $sortable_query .= "&orderby=meta_value_num&order=".$order."&meta_key=";

                    $sortable_query .= "WSS_DATA_GOOGLE";
                    
                break;

                case "pinterest" :

                    $sortable_query .= "&orderby=meta_value_num&order=".$order."&meta_key=";

                    $sortable_query .= "WSS_DATA_PINTEREST";
                    
                break;

                case "twitter" :

                    $sortable_query .= "&orderby=meta_value_num&order=".$order."&meta_key=";

                    $sortable_query .= "WSS_DATA_TWITTER";
                    
                break;

                case "linkedin" :

                    $sortable_query .= "&orderby=meta_value_num&order=".$order."&meta_key=";

                    $sortable_query .= "WSS_DATA_LINKEDIN";
                    
                break;

                case "stumbleupon" :

                    $sortable_query .= "&orderby=meta_value_num&order=".$order."&meta_key=";

                    $sortable_query .= "WSS_DATA_STUMBLEUPON";
                    
                break;

                case "total" :

                    $sortable_query .= "&orderby=meta_value_num&order=".$order."&meta_key=";

                    $sortable_query .= "WSS_DATA_TOTAL";
                    
                break;

                case "title" :

                    $sortable_query .= "&orderby=title&order=".$order;

                break;

                case "date" :

                    $sortable_query .= "&orderby=post_date&order=desc";

                break;

                default : 

                    $sortable_query = "";

                break;

            }

        }
        else {

             $sortable_query .= "&orderby=post_date&order=desc";
        }

        if( 'post' == $post_type ){
            $recentPosts->query( 'post_status=publish&showposts='.$per_page.'&posts_per_page='.$per_page.'&year='.$year.'&monthnum='.$month.'&cat='.$category.'&paged='.$page. $sortable_query );
        }
        if( 'page' == $post_type){
            $recentPosts->query( 'post_status=publish&showposts='.$per_page.'&posts_per_page='.$per_page.'&year='.$year.'&monthnum='.$month.'&post_type='.$post_type.'&paged='.$page. $sortable_query );
        }

        $number_pages = $recentPosts->max_num_pages;

        $data = array();

        while ( $recentPosts->have_posts() ) : 

            $recentPosts->the_post();

            $elem_id = get_the_ID();

            $permalink = get_permalink();

            $count_data = unserialize( get_post_meta( $elem_id, "WSS_DATA", true) );

            if(  $count_data === FALSE ){

                $google_count = $stumbleupon_count = $twitter_count = $facebook_count = $linkedin_count = $pinterest_count = $total = "n/a";
            }
            else {

                $google_count = $count_data["google"];

                $stumbleupon_count = $count_data["stumbleupon"];

                $twitter_count = $count_data["twitter"];

                $facebook_count = $count_data["facebook"];

                $linkedin_count = $count_data["linkedin"];

                $pinterest_count =$count_data["pinterest"];

                $total = $count_data["total"] ;

            }

            $data[] = array(
                "ID" => $elem_id,
                "permalink" => $permalink,
                "title" => get_the_title(),
                "count_twitter" => $twitter_count,
                "count_google" => $google_count,
                "count_facebook" => $facebook_count,
                "count_linkedin" => $linkedin_count,
                "count_stumbleupon" => $stumbleupon_count,
                "count_pinterest" => $pinterest_count,
                "total" => $total

            );

        endwhile;

        $columns = $this->get_columns();

        $hidden = array();

        $sortable = $this->get_sortable_columns( $is_sortable );
        
        $this->_column_headers = array($columns, $hidden, $sortable);
    
        $current_page = $paged;
        
        $total_items = count($all_wss_data);
        
        $this->items = $data;
        
        $this->set_pagination_args( array(
            'total_items' => $total_items,            
            'per_page'    => $per_page,                 
            'total_pages' => ceil($total_items/$per_page)
        ) );

        $this->options = array(
            "post_type" =>$post_type,
            "per_page" => $per_page,
            "cat_perm" => $cat_perm,
            "category" => $category,
            "show_date" => $show_date,
            "page" => $page,
            "orderby" => $orderby,
            "order" => $order,
            "sortable" => $is_sortable,
            "all_data" => $all_wss_data,
            "old_data" => $old_wss_data
        );
    }
    
}