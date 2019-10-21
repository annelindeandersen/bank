console.log('One page only loaded')

$('.page').hide();
$('#home').show()

if (window.location.href.indexOf("/index.php#login") > 0) {
    console.log('show login')
    $('.page').hide();
    $('#login').show()
}

$('.navLink').click( function() {
    // hide all pages
    $('.page').hide();
    // $(this).addClass('active')
    
    // get and show page name by pointing to attribute
    let showPage = $(this).attr('data-showPage')
    $('#'+showPage).show()

} )

$('#showSignup').click( function() {
    $('.page').hide()
    $('#signup').show()
})

$('#showLogin').click( function() {
    $('.page').hide()
    $('#login').show()
})

$('#forgotPassword').click( function(){
    $('.page').hide()
    $('#forgotten').show()
})