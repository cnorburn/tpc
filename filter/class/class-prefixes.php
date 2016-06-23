<?php

/**
 * Created by PhpStorm.
 * User: carl
 * Date: 29/02/2016
 * Time: 13:05
 */
class Prefixes{

    public function __construct(){
    }

    public function getAllPrefixes(){
        return $this->allPrefixes();
    }

    private function allPrefixes (){
        global $wpdb;
        return $wpdb->get_results( "select replace(path,'/','') as path, blog_id as prefix from wp_blogs where public=1 and blog_id>1;");
    }


}