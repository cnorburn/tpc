<?php

if (!class_exists('TPCFilter')):

    class TPCFilter{

        protected $countryId;
        protected $language;
        protected $service;
        protected $sector;
        protected $function='';
        protected $type;

        public function __construct($countryId ,$language, $service, $sector, $type){
            $this->countryId=$countryId;
            $this->language=$language;
            $this->service=$service;
            $this->sector=$sector;
            $this->type=$type;
        }


        public function getFilteredPosts(){

            return $this->filteredPosts();
        }


        private function calculateFunction(){

            $this->function='';

            $this->function= ($this->countryId=='all') ? '0' : '1';
            $this->function.= ($this->language=='all') ? '0' : '1';
            $this->function.= ($this->service=='all') ? '0' : '1';
            $this->function.= ($this->sector=='all') ? '0' : '1';

        }


        private function filteredPosts(){
            $posts=array();

            $this->calculateFunction();

            $postsClass=new TPCPosts($this->countryId, $this->language, $this->service, $this->sector, $this->type);

            switch ($this->function){

                case '0000':

                    return array('posts'=>$postsClass->getAll());

                    break;

                case '0001':

                    $posts= $postsClass->getBySector();
                    //country, language, service by sector

                    $categoryClass=new TPCCategories(0,0,'',$this->sector);
                    $countries=$categoryClass->getCountryByCategory();

                    $categoryClass=new TPCCategories(0,0,array('language'),array($this->sector));
                    $languages=$categoryClass->getCategoryByParentCategory();

                    $categoryClass=new TPCCategories(0,0,array('services'),array($this->sector));
                    $services=$categoryClass->getCategoryByParentCategory();


                    return array('posts'=>$posts,'countries'=>$countries, 'languages'=>$languages, 'services' => $services);

                    break;

                case '0010':

                    $posts= $postsClass->getByService();

                    $categoryClass=new TPCCategories(0,0,'',$this->service);
                    $countries=$categoryClass->getCountryByCategory();

                    $categoryClass=new TPCCategories(0,0,array('language'),$this->service);
                    $languages=$categoryClass->getCategoryByParentCategory();

                    $categoryClass=new TPCCategories(0,0,array('sector'),$this->service);
                    $sectors=$categoryClass->getCategoryByParentCategory();

                    return array('posts'=>$posts,'countries'=>$countries, 'languages'=>$languages, 'sectors' => $sectors);

                    break;

                case '0011':

                    $posts= $postsClass->getByServiceSector();

                    $categoryClass=new TPCCategories(0,0,'',array($this->service,$this->sector));
                    $countries=$categoryClass->getCountryByCategory();

                    $categoryClass=new TPCCategories(0,0,array('language'),array($this->service,$this->sector));
                    $languages=$categoryClass->getCategoryByParentCategory();

                    return array('posts'=>$posts,'countries'=>$countries, 'languages'=>$languages, 'sectors' => array(),'services' => array());

                    break;

                case '0100':

                    $posts= $postsClass->getByLanguage();

                    $categoryClass=new TPCCategories(0,0,'',$this->language);
                    $countries=$categoryClass->getCountryByCategory();

                    $categoryClass=new TPCCategories(0,0,array('services'),$this->language);
                    $services=$categoryClass->getCategoryByParentCategory();

                    $categoryClass=new TPCCategories(0,0,array('sector'),$this->language);
                    $sectors=$categoryClass->getCategoryByParentCategory();

                    return array('posts'=>$posts,'countries'=>$countries, 'languages'=>array(), 'sectors' => $sectors,'services' => $services);

                    break;

                case '0101':

                    $posts= $postsClass->getByLanguageSector();

                    $categoryClass=new TPCCategories(0,0,'',array($this->language,$this->sector));
                    $countries=$categoryClass->getCountryByCategory();

                    $categoryClass=new TPCCategories(0,0,array('services'),array($this->language,$this->sector));
                    $services=$categoryClass->getCategoryByParentCategory();

                    return array('posts'=>$posts,'countries'=>$countries, 'languages'=>array(), 'sectors' => array(),'services' => $services);

                    break;

                case '0110':

                    $posts= $postsClass->getByLanguageService();

                    $categoryClass=new TPCCategories(0,0,'',array($this->language,$this->service));
                    $countries=$categoryClass->getCountryByCategory();

                    $categoryClass=new TPCCategories(0,0,array('sector'),array($this->language,$this->service));
                    $sectors=$categoryClass->getCategoryByParentCategory();

                    return array('posts'=>$posts,'countries'=>$countries, 'languages'=>array(), 'sectors' => $sectors,'services' => array());

                    break;

                case '0111':

                    $posts= $postsClass->getByLanguageServiceSector();

                    $categoryClass=new TPCCategories(0,0,'',array($this->language,$this->sector,$this->service));
                    $countries=$categoryClass->getCountryByCategory();

                    return array('posts'=>$posts,'countries'=>$countries, 'languages'=>array(), 'sectors' => array(),'services' => array());

                    break;

                case '1000':

                    $posts= $postsClass->getByCountry();

                    $categoryClass=new TPCCategories(array($this->countryId),0,array('language'),array());
                    $languages=$categoryClass->getCategoryByParentCategory();

                    $categoryClass=new TPCCategories(array($this->countryId),0,array('services'),array());
                    $services=$categoryClass->getCategoryByParentCategory();

                    $categoryClass=new TPCCategories(array($this->countryId),0,array('sector'),array());
                    $sectors=$categoryClass->getCategoryByParentCategory();

                    return array('posts'=>$posts,'countries'=>array(), 'languages'=>$languages, 'sectors' => $sectors,'services' => $services);

                    break;

                case '1001':

                    $posts= $postsClass->getByCountrySector();

                    $categoryClass=new TPCCategories(array($this->countryId),0,array('language'),array($this->sector));
                    $languages=$categoryClass->getCategoryByParentCategory();

                    $categoryClass=new TPCCategories(array($this->countryId),0,array('services'),array($this->sector));
                    $services=$categoryClass->getCategoryByParentCategory();

                    return array('posts'=>$posts,'countries'=>array(), 'languages'=>$languages, 'sectors' => array() ,'services' => $services);

                    break;

                case '1010':

                    $posts= $postsClass->getByCountryService();

                    $categoryClass=new TPCCategories(array($this->countryId),0,array('language'),array($this->service));
                    $languages=$categoryClass->getCategoryByParentCategory();

                    $categoryClass=new TPCCategories(array($this->countryId),0,array('sector'),array($this->service));
                    $sectors=$categoryClass->getCategoryByParentCategory();

                    return array('posts'=>$posts,'countries'=>array(), 'languages'=>$languages, 'sectors' => $sectors ,'services' => array());
                    break;

                case '1011':

                    $posts= $postsClass->getByCountryServiceSector();

                    $categoryClass=new TPCCategories(array($this->countryId),0,array('language'),array($this->service,$this->sector));
                    $languages=$categoryClass->getCategoryByParentCategory();

                    return array('posts'=>$posts,'countries'=>array(), 'languages'=>$languages, 'sectors' => array() ,'services' => array());

                    break;

                case '1100':

                    $posts= $postsClass->getByCountryLanguage();

                    $categoryClass=new TPCCategories(array($this->countryId),0,array('services'),array($this->language));
                    $services=$categoryClass->getCategoryByParentCategory();

                    $categoryClass=new TPCCategories(array($this->countryId),0,array('sector'),array($this->language));
                    $sectors=$categoryClass->getCategoryByParentCategory();

                    return array('posts'=>$posts,'countries'=>array(), 'languages'=>array(), 'sectors' => $sectors,'services' => $services);


                    break;

                case '1101':

                    $posts= $postsClass->getByCountryLanguageSector();

                    $categoryClass=new TPCCategories(array($this->countryId),0,array('services'),array($this->language,$this->sector));
                    $services=$categoryClass->getCategoryByParentCategory();

                    return array('posts'=>$posts,'countries'=>array(), 'languages'=>array(), 'sectors' => array(),'services' => $services);

                    break;

                case '1110':

                    $posts= $postsClass->getByCountryLanguageService();

                    $categoryClass=new TPCCategories(array($this->countryId),0,array('sector'),array($this->language,$this->service));
                    $sectors=$categoryClass->getCategoryByParentCategory();

                    return array('posts'=>$posts,'countries'=>array(), 'languages'=>array(), 'sectors' => $sectors,'services' => array());

                    break;

                case '1111':

                    $posts= $postsClass->getByCountryLanguageServiceSector();

                    return array('posts'=>$posts,'countries'=>array(), 'languages'=>array(), 'sectors' => array(),'services' => array());

                    break;


            }



        }


    }


endif;


