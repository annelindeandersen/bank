console.log('signup')

$('#frmSignup').submit( function(){
    console.log('SKE NOGET')

    $.ajax({
        method: "POST",
        url: "apis/api-signup.php",
        data: $('#frmSignup').serialize(),
        dataType: 'JSON'
    }).
    done( function(jData) {
        if (jData.status == 0) {
            swal({ title:"Error!", text: jData.message , icon: "warning"});
        } 

        if (jData.status == 1) {
            swal({ title:"CONGRATS", text:"You have signed up. Check your email for activation link.", icon: "success"});
        }
    }).
    fail( function() {
        console.log('error')
        swal({ title:"SYSTEM UPDATE", text:"System is under maintainance code: "+jData.code, icon: "error"});
    })
return false
})