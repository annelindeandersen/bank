console.log('apply for loan')

$('#frmApplyForLoan').submit(function() {
console.log('trykket på apply loan')

$.ajax({
    method: "POST",
    url: 'apis/api-apply-for-loan.php',
    data: $('#frmApplyForLoan').serialize(),
    dataType : "JSON"
  }).
  done(function( jLoans ){
    console.log('success')
    console.log( jLoans )
    if (jLoans.status == -1) {
      console.log(jLoans)
      swal({ title: "Oops!", icon: "warning", text: "Loan must be a number between 1 and 1.000.000." });
    } 

    if (jLoans.status == 1) {
      console.log(jLoans)
      swal({ title: "Success!", icon: "success", text: "Application sent. See it on your profile and wait for activation." });
    }
  }).
  fail(function(){ 
      console.log('FAIL') 
      swal({ title: "Error!", icon: "error", text: "Loan application did not go through. Contact bank." });
    })
return false
})