document.getElementById('submitEditPassword').addEventListener('click', function() {
    $.post('controller.php', {
       submit: "changePassword",
       oldPass: document.getElementById('oldPass').value,
       newPass: document.getElementById('newPass').value,
       newPass2: document.getElementById('newPass2').value
    }, 
    function(data, status) {
        let oldPassError = document.getElementById('oldPassError');
        let newPassError = document.getElementById('newPassError');
        
        if(typeof data.oldPassValidation === 'undefined' && typeof data.newPassValidation === 'undefined') {
            window.location.reload(true);
        }
        
        if(typeof data.oldPassValidation !== 'undefined') {
            oldPassError.innerHTML = data.oldPassValidation;
        }
        else {
            oldPassError.innerHTML = '&nbsp';
        }
        if(typeof data.newPassValidation !== 'undefined') {
            newPassError.innerHTML = data.newPassValidation;
        }
        else {
            newPassError.innerHTML = '&nbsp';
        } 
    },
    "json"
    );
});

document.getElementById('resetEditPassword').addEventListener('click', function() {
    $('#editPassword input').val('');
});

document.getElementById('submitEditEmail').addEventListener('click', function() {
    let emailError = document.getElementById('emailError');
    $.post('controller.php', {
       submit: "changeEmail",
       newEmail: document.getElementById('newEmail').value
    }, 
    function(data, status) {
        console.log(data);
        if(data !== '') {
            emailError.innerHTML = data;
        }
        else {
            window.location.reload(true);
        }
    },
    );
});

document.getElementById('resetEditEmail').addEventListener('click', function() {
    $('#editEmail input').val('');
});

