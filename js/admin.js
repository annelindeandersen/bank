console.log('One page only loaded')

$('.page').hide();
$('#accounts').show()

if (window.location.href.indexOf("/admin.php#loans") > 0) {
    console.log('show loans')
    $('.navLink').removeClass('active');
    $('.page').hide();
    $('#loans').show()
    $('.loans').addClass('active')
}

$('.navLink').click( function() {
    $('.page').hide().removeClass('active');
    $('.navLink').removeClass('active');
    $(this).addClass('active')
    
    // get and show page name by pointing to attribute
    let showPage = $(this).attr('data-showPage')
    $('#'+showPage).show()

} )

// ************** TRANSFER *********************

$('#frmAdminTransfer').submit(function() {
    console.log('trykket på create')
    
    $.ajax({
        method: "POST",
        url: 'apis/api-admin-transfer.php',
        data: $('#frmAdminTransfer').serialize(),
        dataType : "JSON"
      }).
      done(function( jData ){
        console.log('success')

        if (jData.status == -1) {
          console.log(jData)
          swal({ title: "Error!", icon: "warning", text: "ID/Phone must be a number of 8 digits" });
        } 

        if (jData.status == 0) {
          console.log(jData)
          swal({ title: "Error!", icon: "warning", text: "Transfer must be a number between 1 and 1.000.000" });
        }

        if (jData.status == 1) {
          console.log(jData)
          swal({ title: "Success!", icon: "success", text: "You transfered money successfully" });
        }
    
      }).
      fail(function(){ 
          console.log('FAIL') 
          swal({ title: "Error!", icon: "error", text: "Something went wrong. Try later." });
        })
    return false
})

// ************** SEND MAIL *********************

$('#frmAdminSendMail').submit(function() {
    console.log('trykket på create')
    
    $.ajax({
        method: "POST",
        url: 'apis/api-admin-send-mails.php',
        data: $('#frmAdminSendMail').serialize(),
        dataType : "JSON"
        }).
        done(function( jData ){
        console.log('success')

        if (jData.status == 0) {
            console.log(jData)
            swal({ title: "Error!", icon: "warning", text: jData.message });
        }

        if (jData.status == 1) {
            console.log(jData)
            swal({ title: "Success!", icon: "success", text: jData.message });
        }
    
        }).
        fail(function(){ 
            console.log('FAIL') 
            swal({ title: "Error!", icon: "error", text: "Something went wrong. Try later." });
        })
    return false
})
    