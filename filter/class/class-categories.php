<?php

if (!class_exists('TPCCategories')) {

    class TPCCategories
    {

        protected $prefix;
        protected $id;
        protected $name;

        protected $category;

        public function __construct($prefix = 0, $id = 0, $name, $category=array())
        {
            $this->prefix = $prefix;
            $this->id = $id;
            $this->name = $name;
            $this->category = $category;
        }

        public function getAllCategories()
        {
            return $this->allCategories();
        }

        public function getCategoriesByCountry()
        {
            return $this->allCategoriesByCountry();
        }


        private function allCategoriesByCountry()
        {

            $categories = array();
            $id = $this->parentId();

            if (!empty($id[0]->id)) {
                $this->id = $id[0]->id;
                $categories = $this->category();
            }

            return $categories;

        }


        public function getCountryByCategory()
        {
            return $this->countryByCategory();
        }

        public function getCategoryByParentCategory()
        {
            return $this->categoryByParentCategory();

        }


        private function countryByCategory()
        {

            $countries = array();

            $prefixes=$this->makePrefixes();

            foreach ($prefixes as $prefix) {

                $this->prefix = (isset($prefix->prefix)) ? $prefix->prefix : $prefix;

                if ($this->hasCategory()) {
                    $countries[] = $prefix->prefix;
                }

            }


            $countryClass = new TPCCountries($countries);
            return $countryClass->getCountryByPrefix();

        }


        private function categoryByParentCategory()
        {

            $categories = array();

            $prefixes=$this->makePrefixes();

            foreach ($prefixes as $prefix) {

                $this->prefix = (isset($prefix->prefix)) ? $prefix->prefix : $prefix;

                $categories[] = $this->hasCategoryAndOtherParent();

            }

            if (!empty($categories)) {
                $categories = call_user_func_array('array_merge', $categories);
                return array_values(array_unique($categories, SORT_REGULAR));
            } else {
                return array();
            }


        }


        private function allCategories()
        {

            $categories = array();

            $prefixes=$this->makePrefixes();

            foreach ($prefixes as $prefix) {

                $this->prefix = (isset($prefix->prefix)) ? $prefix->prefix : $prefix;

                $id = $this->parentId();

                if (!empty($id)) {
                    $this->id = $id[0]->id;
                    $categories[] = $this->category();
                }

            }

            if (!empty($categories)) {
                $categories = call_user_func_array('array_merge', $categories);
                return array_values(array_unique($categories, SORT_REGULAR));
            } else {
                return array();
            }

        }

        public function parentId()
        {
            global $wpdb;

            $table1 = 'wp_' . $this->prefix . '_term_taxonomy';
            $table2 = 'wp_' . $this->prefix . '_terms';

            return $wpdb->get_results("SELECT term_id as id FROM " . $table2 . " where term_id in (SELECT term_id FROM " . $table1 . " where parent = 0 and taxonomy = 'category' and name='" . $this->name . "');");

        }

        public function category()
        {
            global $wpdb;

            $table1 = 'wp_' . $this->prefix . '_term_taxonomy';
            $table2 = 'wp_' . $this->prefix . '_terms';

            return $wpdb->get_results("SELECT name, slug FROM " . $table2 . " where term_id in (SELECT term_id FROM " . $table1 . " where parent =" . $this->id . " and taxonomy = 'category' and count>0) order by name asc;");

        }


        private function hasCategory()
        {

            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => $this->category,
                        'operator' => 'AND'
                    )
                )
            );

            switch_to_blog($this->prefix);

            $_posts = new WP_Query($args);

            $bool = ($_posts->have_posts()) ? true : false;

            restore_current_blog();

            return $bool;


        }


        private function hasCategoryAndOtherParent()
        {

            $categories = array();

            switch_to_blog($this->prefix);

            $this->terms=array();
            foreach ($this->name as $term) {
                $_term = get_term_by('name', $term, 'category');
                if(!empty($_term))
                    $this->terms[] = get_term_children($_term->term_id, 'category');
            }
            if(!empty($this->terms))
                $this->terms = call_user_func_array('array_merge', $this->terms);

            if(empty($this->category)){
                $taxQuery=array();
            }else{
                $taxQuery=array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => $this->category,
                        'operator' => 'AND'
                    )
                );
            }

            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'category__in' => $this->terms,
                'tax_query' => $taxQuery
            );

            $_posts = new WP_Query($args);

            if ($_posts->have_posts()) {
                while ($_posts->have_posts()) : $_posts->the_post();
                    $res = array_intersect($this->terms, wp_get_post_categories(get_the_ID()));
                    if (!empty($res)) {
                        $category = get_term_by('id', reset($res), 'category');
                        $categories[] = array('name' => $category->name, 'slug' => $category->slug);
                    }
                endwhile;
            }

            restore_current_blog();

            return $categories;

        }


        private function makePrefixes(){

            if(empty($this->prefix) || $this->prefix==0){
                $prefixClass = new Prefixes();
                $prefixes = $prefixClass->getAllPrefixes();
            }else{
                $prefixes=array('prefix'=>$this->prefix[0]);
            }

            return $prefixes;

        }


    }
}