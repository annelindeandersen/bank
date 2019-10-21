console.log('One page only loaded')

$('.page').hide();
$('#profile').show()

$('.navLink').click( function() {
    // hide all pages
    $('.page').hide().removeClass('active');
    $('.navLink').removeClass('active');
    $(this).addClass('active')
    
    // get and show page name by pointing to attribute
    let showPage = $(this).attr('data-showPage')
    $('#'+showPage).show()
} )