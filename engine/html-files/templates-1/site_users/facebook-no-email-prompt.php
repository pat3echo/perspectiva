<script type="text/javascript">
    getMail();
    
    function getMail(){
        var email = prompt( 'Please enter your email address, as Facebook did not provide one' );
        
        var fail = true;
        if( email ){
            var nmail = mail(email);
            if( nmail ){
                //redirect to process request
                if (!window.location.origin) {
                  window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
                }
                window.location.href = window.location.origin + '/facebook/?email='+nmail;
                fail = false;
            }
        }
        
        if(fail)
            getMail();
    };
    //confirm email address
    function mail(email){
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if( email.length<1 || !emailReg.test( email ) ) {
            alert('The email address you provided is invalid\n\nPlease provide a valid email address');
            return false;
        } else {
            return email;
        }
    };
</script>