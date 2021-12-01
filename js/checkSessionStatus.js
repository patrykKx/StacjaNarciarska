function checkLoginStatus(){
    $.post("controller.php", {
        submit: "checkSessionStatus"
    },
    function(data){
        if(!data) window.location = "index.php"; 

        setTimeout(function(){  checkLoginStatus(); }, 3000); 
    });
}
checkLoginStatus();


