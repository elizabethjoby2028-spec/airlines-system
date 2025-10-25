function showRegister(){
    document.getElementById('login-section').style.display='none';
    document.getElementById('register-section').style.display='block';
}

function showLogin(){
    document.getElementById('login-section').style.display='block';
    document.getElementById('register-section').style.display='none';
}

/* Flight Search Validation */
document.getElementById('searchForm')?.addEventListener('submit', function(e){
    let source = this.source.value.trim();
    let destination = this.destination.value.trim();
    if(source === "" || destination === ""){
        alert("Please enter both source and destination!");
        e.preventDefault();
    }
    if(source.toLowerCase() === destination.toLowerCase()){
        alert("Source and destination cannot be the same!");
        e.preventDefault();
    }
});

/* Booking Form Validation */
document.querySelectorAll('.booking-form').forEach(function(form){
    form.addEventListener('submit', function(e){
        let seat = this.seat_no.value.trim();
        if(seat === ""){
            alert("Please enter seat number!");
            e.preventDefault();
        }
    });
});
