$=jQuery;

$(function() {
    $('.nav-desktop .sub-menu').wrap( "<div class='sub-wrapper'></div>" );

    $('.nav-desktop li.menu-item-has-children').wrapInner("<div class='anchor-wrapper'></div>")
    $(".anchor-wrapper").hover(function(){

        $(".anchor-wrapper a").removeClass('hover');

        $(this).find('a:first').toggleClass('hover');

        //IE8 not hiding other menus
        $('.sub-wrapper ul.sub-menu').hide();

        $(this).find('.sub-wrapper').animate(
            { height: $(this).find('.sub-wrapper ul').height() }
            ,200);

        $(this).find('.sub-wrapper ul.sub-menu').show();

    }, function() {

        $(this).find('a:first').toggleClass('hover');
        $(this).find('.sub-wrapper').animate({ height: "0" },200);
        //IE8 not hiding
        $(this).find('.sub-wrapper ul.sub-menu').hide();
    });


    $('ul.slimmenu').slimmenu({
        resizeWidth: '1140', /* Navigation menu will be collapsed when document width is below this size or equal to it. */
        collapserTitle: 'Menu', /* Collapsed menu title. */
        animSpeed: 100, /* Speed of the submenu expand and collapse animation. */
        easingEffect: 'easeInElastic', /* Easing effect that will be used when expanding and collapsing menu and submenus. */
        indentChildren: false, /* Indentation option for the responsive collapsed submenus. If set to true, all submenus will be indented with the value of the option below. */
        childrenIndenter: '&raquo;' /* Responsive submenus will be indented with this character according to their level. */
    });

    //tis hidden in css, now show it, was causing screen flicker in osx chrome
    $('.nav-mobile ul.menu li').css('visibility','visible').css('display','block');

    //Below sets the widths of the submenu items according to the letter count title
    //Allows dynamic resizing, before it was hard coded in the CSS
    var pixelsPerLetter=9;
    var toAdd=20;
    $('.nav-desktop').each(function() {
        $(this).find('.sub-wrapper').each(function () {
            var _width = 0;
            $(this).find('li').each(function () {
                if ($(this).text().length > _width) _width = $(this).text().length;
            });
            $(this).css('width', (_width * pixelsPerLetter) + toAdd);
            $(this).find('li').each(function () {
                $(this).css('width', (_width * pixelsPerLetter) + toAdd);
            });
        });
    });

});
