<?php
/**
 * Template Name: News Local Filter
 *
 * @package WordPress
 * @subpackage TPC
 */


get_header();


$Sectors=new TPCCategories(0,0,'Sector');
$sectors=$Sectors->getAllCategories();

$Services=new TPCCategories(0,0,'Services');
$services=$Services->getAllCategories();

?>

<div class="container blog">
    <div class="col-left">
        <div id="content" class="site-content">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                    <h1>Local News Filter (UK)</h1>

                    <div class="filter-wrapper">

                        <select id="services" name="services" class="filter">
                            <option value="all">All services</option>
                            <?php
                            foreach ($services as $service) {
                                $selected = '';
                                if(!empty($service)){
                                    echo('<option value="' . $service->slug . '"' . $selected . '> ' . $service->name . '</option>');
                                }
                            }
                            ?>
                        </select>

                        <select id="sectors" name="sectors" class="filter">
                            <option value="all">All sectors</option>
                            <?php
                            foreach ($sectors as $sector) {
                                $selected = '';
                                if(!empty($sector)){
                                    echo('<option value="' . $sector->slug . '"' . $selected . '> ' . $sector->name . '</option>');
                                }
                            }
                            ?>
                        </select>

                    </div>

                    <section id="posts"></section>

                </main>

            </div>


        </div>

    </div>
</div>



<script type="text/javascript">

    $=jQuery;
    var ajaxurl, wpAjax;
    $(function() {

        var ajaxUrl = "<?php echo admin_url('admin-ajax.php')?>";
        var selected={}

        function renderPosts(posts){
            var list = $("section#posts").empty().append('<ul class="dynamic-posts"></ul>').find('ul');
            for(var i = 0, l = posts.data.filtered.posts.length; i < l; i++) {
                var post = posts.data.filtered.posts[i];
                var img=(post.img=== false) ? '<div class="image"></div>' : '<div class="image"><img src="' + post.img + '" alt=""/></div>'
                list.append('<li><a class="z" href="' + post.link + '">' + img + '<div class="text"><h1>' + post.title + '</h1><p>' + post.excerpt +  '</p></div></a></li>');
            }
        }


        function renderSelects(posts){

            var sectors=''
            var services=''

            if(!selected.services){
                if(posts.services.length > 0){
                    services+='<option value="all">All services</option>'
                    for(var i = 0, l = posts.services.length; i < l; i++) {
                        var service = posts.services[i];
                        services+='<option value=' + service.slug + '>' + service.name + '</option>';
                    }
                }else{
                    var slug=$("#services option:selected").val();
                    var name=$("#services option:selected").text()
                    services+='<option value=' + slug + '>' + name + '</option>'
                }
                $('select#services').empty().append(services)
            }

            if(!selected.sectors){
                if(posts.sectors.length > 0){
                    sectors+='<option value="all">All sectors</option>'
                    for(var i = 0, l = posts.sectors.length; i < l; i++) {
                        var sector = posts.sectors[i];
                        sectors+='<option value=' + sector.slug + '>' + sector.name + '</option>';
                    }
                }else{
                    var slug=$("#sectors option:selected").val();
                    var name=$("#sectors option:selected").text()
                    sectors+='<option value=' + slug + '>' + name + '</option>'
                }
                $('select#sectors').empty().append(sectors)
            }

        }

        $.getJSON('/news/country/language/service/sector/4/english/all/all', function (posts) {
            //on page render top 3 posts
            if (posts.success === true) {
                if(posts.data.filtered.posts.length>0){
                    renderPosts(posts)
                }
            }
        });


        //store languages in cache object
        var serviceCache=[]
        $("#services > option").each(function() {
            serviceCache.push({
                value: this.value,
                text: this.text
            });
        });
        //store sectors in cache object
        var sectorCache=[]
        $("#sectors > option").each(function() {
            sectorCache.push({
                value: this.value,
                text: this.text
            });
        });


        $('select.filter').change(function(){

            //TODO get the blog id and language from the DOM
            var endpoint='4/english/' + $("#services option:selected").val() + '/' + $("#sectors option:selected").val()

            selected={
                services : ($(this).attr('name')==='services') ? true : false,
                sectors : ($(this).attr('name')==='sectors') ? true : false
            }

            $.getJSON('/news/country/language/service/sector/' + endpoint, function (posts) {

                $("section#posts").empty()

                if(posts.success===true) {
                    if (posts.data.filtered.posts.length > 0) {
                        renderPosts(posts)

                        renderSelects(posts.data.filtered)

                    }
                }

            });

        });


    });


</script>
