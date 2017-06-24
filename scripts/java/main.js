/*
$('.message a').click(function(){
   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
});
*/

function validateForm()
{
    var x = document.forms["form"]["login-form"]["username"].value;
    console.log(x);
    return true;
}