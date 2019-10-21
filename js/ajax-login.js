console.log('login')

$('#frmLogin').submit( function(){

    $.ajax({
        method: "POST",
        url: "apis/api-login.php",
        data: $('#frmLogin').serialize(),
        dataType: 'JSON'
    }).
    done( function(jData) {
        if (jData.status == -2) {
            console.log(jData)
            swal({ title: "Sorry!", icon: "warning", text: "Password and Phone do not match." });
        } //else { location.href = 'profile' }

        if (jData.status == -1) {
            console.log(jData)
            swal({ title: "Password incorrect!", icon: "error", text: jData.message });
        } //else { location.href = 'profile' }

        if (jData.status == 0) {
            console.log(jData)
            swal({ title: "Account not active!", icon: "error", text: "Please check your email for activation link." });
        }

        if (jData.status == 1) {
            console.log(jData)
            swal({ title: "Password incorrect!", icon: "warning", text: jData.message });
        }

        if (jData.status == 2) {
            console.log(jData)
            swal({ title: "Account not active!", icon: "error", text: "Please check your email for activation link." });
        }
        if (jData.status == 3) {
            console.log(jData)
            location.href = 'profile'
        }
    }).
    fail( function() {
        console.log('error')
    })
return false
})