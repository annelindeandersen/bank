<?php 
    require_once('top-logged-out.php');
?>

<div id="home" class="page"> 

    <h1>WELCOME TO <div>BANK OF LINDE</div></h1>

</div>

<div id="login" class="page">    

    <div id="frmLoginBox">
        <h2>LOGIN</h2>
        <form id="frmLogin">
            <div class="errorMessage"></div>
            <h4>Phone nr: </h4>
            <input name="txtLoginPhone" type="number" data-validate="yes" data-type="integer">
            <h4>Password: </h4>
            <input name="txtLoginPassword" type="password" data-validate="yes" data-type="string" data-min="4" data-max="20">
            <br><br>
            <button>LOG IN</button>
        </form>
        <p id="showSignup" class="account">No account yet? Sign up here.</p>
        <p id="forgotPassword" class="account">Did you forget your password?</p>
    </div>

</div> 

<div id="signup" class="page"> 

    <div id="frmSignupBox">
        <h2>SIGNUP</h2>
        <form id="frmSignup">
            <div class="errorMessage"></div>
            <h4>Name: </h4>
            <input name="txtSignupName" type="text" data-validate="yes" data-type="string" data-min="2" data-max="20">
            <h4>Last name: </h4>
            <input name="txtSignupLastName" type="text" data-validate="yes" data-type="string" data-min="2" data-max="20">
            <h4>Phone: </h4>
            <input name="txtSignupPhone" type="number" data-validate="yes" data-type="integer" data-min="8" data-max="8">
            <h4>Email: </h4>
            <input name="txtSignupEmail" type="text">
            <h4>Password: </h4>
            <input name="txtSignupPassword" type="password" data-validate="yes" data-type="string" data-min="4" data-max="20">
            <h4>Confirm password: </h4>
            <input name="txtSignupConfirmPassword" type="password" data-validate="yes" data-type="string" data-min="4" data-max="20">
            <br><br>
            <button>SIGN UP</button>
        </form>
        <p id="showLogin" class="account">Already have an account? Login here.</p>
    </div>

</div>

<div id="forgotten" class="page">
    <h1>OOPS. I FORGOT MY PASSWORD.</h1>
    <form id="forgotMyPassword" action="apis/api-forgot-password.php" method="GET">
        <div class="errorMessage"></div>
        <h4>Phone number:</h4>
        <input name="txtForgotPhone" type="number" data-validate="yes" data-type="integer" data-min="8" data-max="8"><br>
        <button>SEND NEW PASSWORD</button>
    </form>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="js/logged-out-one-page-only.js"></script>
<script src="js/validate.js"></script>
<script src="js/ajax-login.js"></script>
<script src="js/ajax-signup.js"></script>

</body>
</html>
