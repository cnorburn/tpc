<?php
/**
 * Created by PhpStorm.
 * User: carl
 * Date: 08/03/2016
 * Time: 11:40
 */

if (!class_exists('TPCPosts')) {

    class TPCPosts
    {

        protected $countryId;
        protected $language;
        protected $sector;
        protected $service;
        protected $type;
        protected $posts=array();

        public function __construct($countryId, $language, $service, $sector,$type)
        {
            $this->countryId = $countryId;
            $this->language = $language;
            $this->service = $service;
            $this->sector = $sector;
            $this->type=$type;
        }


        public function getAll()
        {

            $posts=array();
            $prefixClass = new Prefixes();
            $prefixes = $prefixClass->getAllPrefixes();

            foreach ($prefixes as $prefix) {
                $this->countryId = $prefix->prefix;
                $posts[] = $this->byCountry();
            }

            return $this->sortPosts($posts,true);
        }


        public function getBySector()
        {

            $posts=array();
            $prefixClass = new Prefixes();
            $prefixes = $prefixClass->getAllPrefixes();

            foreach ($prefixes as $prefix) {
                $this->countryId = $prefix->prefix;
                $posts[] = $this->getPosts($this->args(array($this->sector)));
            }

            return $this->sortPosts($posts,true);
        }


        public function getByService()
        {

            $posts=array();
            $prefixClass = new Prefixes();
            $prefixes = $prefixClass->getAllPrefixes();

            foreach ($prefixes as $prefix) {
                $this->countryId = $prefix->prefix;
                $posts[] = $this->getPosts($this->args(array($this->service)));
            }

            return $this->sortPosts($posts,true);
        }

        public function getByServiceSector()
        {

            $posts=array();
            $prefixClass = new Prefixes();
            $prefixes = $prefixClass->getAllPrefixes();

            foreach ($prefixes as $prefix) {
                $this->countryId = $prefix->prefix;
                $posts[] = $this->getPosts($this->args(array($this->sector,$this->service)));
            }

            return $this->sortPosts($posts,true);
        }

        public function getByLanguage()
        {

            $posts=array();
            $prefixClass = new Prefixes();
            $prefixes = $prefixClass->getAllPrefixes();

            foreach ($prefixes as $prefix) {
                $this->countryId = $prefix->prefix;
                $posts[] = $this->getPosts($this->args(array($this->language)));
            }

            return $this->sortPosts($posts,true);
        }


        public function getByLanguageSector()
        {

            $posts=array();
            $prefixClass = new Prefixes();
            $prefixes = $prefixClass->getAllPrefixes();

            foreach ($prefixes as $prefix) {
                $this->countryId = $prefix->prefix;
                $posts[] = $this->getPosts($this->args(array($this->language,$this->sector)));
            }

            return $this->sortPosts($posts,true);
        }

        public function getByLanguageService()
        {

            $posts=array();
            $prefixClass = new Prefixes();
            $prefixes = $prefixClass->getAllPrefixes();

            foreach ($prefixes as $prefix) {
                $this->countryId = $prefix->prefix;
                $posts[] = $this->getPosts($this->args(array($this->language,$this->service)));
            }

            return $this->sortPosts($posts,true);
        }

        public function getByLanguageServiceSector()
        {

            $posts=array();
            $prefixClass = new Prefixes();
            $prefixes = $prefixClass->getAllPrefixes();

            foreach ($prefixes as $prefix) {
                $this->countryId = $prefix->prefix;
                $posts[] = $this->getPosts($this->args(array($this->language,$this->service,$this->sector)));
            }

            return $this->sortPosts($posts,true);
        }

        public function getByCountry()
        {
            return $this->sortPosts($this->byCountry());
        }


        public function getByCountrySector()
        {
            return $this->sortPosts($this->getPosts($this->args(array($this->sector))));
        }

        public function getByCountryService()
        {

            return $this->sortPosts($this->getPosts($this->args(array($this->service))));
        }

        public function getByCountryServiceSector()
        {
            return $this->sortPosts($this->getPosts($this->args(array($this->sector,$this->service))));
        }

        public function getByCountryLanguage()
        {
            return $this->sortPosts($this->getPosts($this->args(array($this->language))));
        }


        public function getByCountryLanguageSector()
        {
            return $this->sortPosts($this->getPosts($this->args(array($this->language, $this->sector))));
        }

        public function getByCountryLanguageService()
        {
            return $this->sortPosts($this->getPosts($this->args(array($this->language,$this->service))));

        }


        public function getByCountryLanguageServiceSector()
        {
            return $this->sortPosts($this->getPosts($this->args(array($this->language, $this->service, $this->sector))));
        }


        private function byCountry()
        {

            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 3,
                'orderby' => 'post_date',
                'order' => 'DESC',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => $this->type,
                        'operator' => 'IN'
                        )
                    )
                );

            return $this->getPosts($args);

        }

        private function args($term)
        {

            return array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 3,
                'orderby' => 'post_date',
                'order' => 'DESC',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => array_merge($term,array($this->type)),
                        'operator' => 'AND'
                    )
                )
            );

        }

        private function getPosts($args)
        {

            $posts=array();

            switch_to_blog($this->countryId);

            $_posts = new WP_Query($args);
            if ($_posts->have_posts()) {
                while ($_posts->have_posts()) : $_posts->the_post();
                    $posts[] = array('link' => get_the_permalink(), 'img' => getImg(get_the_ID()), 'title' => get_the_title(), 'excerpt' => get_the_excerpt(), 'date' => date("d-m-Y", strtotime(str_replace("/", "-", get_the_date()))), 'site' => $this->countryId);
                endwhile;
            }

            restore_current_blog();

            return $posts;

        }

        private function sortPosts($posts,$merge=false){

            if(!empty($posts)){

                if($merge)
                    $posts= call_user_func_array('array_merge', $posts);

                usort($posts, 'date_compare');
                return array_slice($posts, 0, 3);

            }else{

                return array();
            }

        }



    }



}