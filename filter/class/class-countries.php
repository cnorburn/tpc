<?php
/**
 * Created by PhpStorm.
 * User: carl
 * Date: 18/02/2016
 * Time: 14:31
 */


if (!class_exists('TPCCountries')) {

    class TPCCountries{

        protected $prefixes=array();

        public function __construct($prefixes=array()){
            $this->prefixes=$prefixes;
        }

        public function getAllCountries(){
            return $this->allCountries();
        }

        public function getCountryByPrefix(){
            global $wpdb;
            $countries=array();

            foreach($this->prefixes as $prefix){

                $table = 'wp_' . $prefix . '_options';

                $res = $wpdb->get_results("select option_value as country from " . $table . " where option_name='SiteCountry'");

                if (!empty($res)) {
                    $countries[]= (object) array('country' => $res[0]->country,'id' => $prefix );
                }

            }

            return $countries;

        }


        private function getAllSitesId(){
            global $wpdb;
            return $wpdb->get_results("select blog_id as id,replace(path,'/','') as path from wp_blogs where public=1 and blog_id>1;");
        }

        private function allCountries(){
            global $wpdb;

            $id = $this->getAllSitesId();

            $countries = array();

            if (!empty($id)) {

                foreach ($id as $_id) {

                    $table = 'wp_' . $_id->id . '_options';

                    $res = $wpdb->get_results("select option_value as country from " . $table . " where option_name='SiteCountry'");

                    if (!empty($res)) {
                        $countries[]= (object) array('country' => $res[0]->country,'path' => $_id->path,'id' => $_id->id );
                    }

                }

            }

            usort($countries, array($this, "sortCountries"));
            return $countries;

        }


        private function sortCountries($a, $b){
            return ($a->country < $b->country) ? -1 : 1;
        }


    }


}
