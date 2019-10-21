console.log('create card')

$('#frmNewCreditCard').submit(function() {
console.log('trykket på create card')

$.ajax({
    method: "POST",
    url: 'apis/api-create-card.php',
    data: $('#frmNewCreditCard').serialize(),
    dataType : "JSON"
  }).
  done(function( jData ){
    console.log('success')
    console.log( jData )

    if ( $('#cardType').val() == 'chooseType') {
      swal({ title: "Something went wrong!", icon: "warning", text: "Choose one of the options please." });
    }

    if ( $('#cardType').val() != 'chooseType') {
      swal({ title: "Success!", icon: "success", text: "Credit card created. See it on your profile" });
    }

  }).
  fail(function(){ 
      console.log('FAIL') 
      swal({ title: "Something went wrong!", icon: "error", text: "Could not create credit card. Contact bank for assistance." });
    })
return false
})