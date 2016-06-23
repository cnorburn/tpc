<?php

if (!class_exists('TPCBlog_Enpoint')):
    class TPCBlog_Enpoint{

        const ENDPOINT_NAME = 'blogs/country/language/service/sector'; // endpoint to capture
        const ENDPOINT_QUERY_NAME = '__blogs_country_language_service_sector'; // turns to param

        public function run(){
            add_filter('query_vars', array($this, 'add_query_vars'), 0);
            add_action('parse_request', array($this, 'sniff_requests'), 0);
            add_action('init', array($this, 'add_endpoint'), 0);
        }

        public function add_query_vars($vars){
            $vars[] = static::ENDPOINT_QUERY_NAME;
            $vars[] = 'countryId';
            $vars[] = 'language';
            $vars[] = 'service';
            $vars[] = 'sector';
            return $vars;
        }

        public function add_endpoint(){
            add_rewrite_rule('^' . static::ENDPOINT_NAME . '/([^/]+)/([^/]+)/([^/]+)/([^/]+)/?$', 'index.php?' . static::ENDPOINT_QUERY_NAME . '=1&countryId=$matches[1]&language=$matches[2]&service=$matches[3]&sector=$matches[4]', 'top');

        // --->
            flush_rewrite_rules(true); //// <---------- REMOVE THIS WHEN DONE TESTING
        // --->

        }

        public function sniff_requests($wp_query){
            global $wp;
            if (isset($wp->query_vars[static::ENDPOINT_QUERY_NAME])) {
                $this->handle_request(); // handle it
            }
        }

        protected function handle_request(){
            global $wp;

            $countryId=$wp->query_vars['countryId'];
            $language=$wp->query_vars['language'];
            $service=$wp->query_vars['service'];
            $sector=$wp->query_vars['sector'];

            header("Access-Control-Allow-Origin: *");

            //creat a class, class-filter.php, pass all four vars, let it calculate which function, call class-post and pass back results

            $posts=new TPCFilter($countryId, $language, $service, $sector,'blog-post');

            wp_send_json_success(array('countryId' => $countryId, 'language'=>$language,'service'=>$service,'sector'=>$sector,'filtered'=>$posts->getFilteredPosts()));

            die();
        }
    }

    $ep = new TPCBlog_Enpoint();

    $ep->run();

endif;

if (!class_exists('TPCNews_Enpoint')):
    class TPCNews_Enpoint{

        const ENDPOINT_NAME = 'news/country/language/service/sector'; // endpoint to capture
        const ENDPOINT_QUERY_NAME = '__news_country_language_service_sector'; // turns to param

        public function run(){
            add_filter('query_vars', array($this, 'add_query_vars'), 0);
            add_action('parse_request', array($this, 'sniff_requests'), 0);
            add_action('init', array($this, 'add_endpoint'), 0);
        }

        public function add_query_vars($vars){
            $vars[] = static::ENDPOINT_QUERY_NAME;
            $vars[] = 'countryId';
            $vars[] = 'language';
            $vars[] = 'service';
            $vars[] = 'sector';
            return $vars;
        }

        public function add_endpoint(){
            add_rewrite_rule('^' . static::ENDPOINT_NAME . '/([^/]+)/([^/]+)/([^/]+)/([^/]+)/?$', 'index.php?' . static::ENDPOINT_QUERY_NAME . '=1&countryId=$matches[1]&language=$matches[2]&service=$matches[3]&sector=$matches[4]', 'top');

            // --->
            flush_rewrite_rules(true); //// <---------- REMOVE THIS WHEN DONE TESTING
            // --->

        }

        public function sniff_requests($wp_query){
            global $wp;
            if (isset($wp->query_vars[static::ENDPOINT_QUERY_NAME])) {
                $this->handle_request(); // handle it
            }
        }

        protected function handle_request(){
            global $wp;

            $countryId=$wp->query_vars['countryId'];
            $language=$wp->query_vars['language'];
            $service=$wp->query_vars['service'];
            $sector=$wp->query_vars['sector'];

            header("Access-Control-Allow-Origin: *");

            //creat a class, class-filter.php, pass all four vars, let it calculate which function, call class-post and pass back results

            $posts=new TPCFilter($countryId, $language, $service, $sector,'news');

            wp_send_json_success(array('countryId' => $countryId, 'language'=>$language,'service'=>$service,'sector'=>$sector,'filtered'=>$posts->getFilteredPosts()));

            die();
        }
    }

    $ep = new TPCNews_Enpoint();

    $ep->run();

endif;




