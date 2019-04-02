function AddPhone2(){
    document.getElementById('addphone2').style.visibility = 'hidden';
    document.getElementById('phonerow2').style.visibility = 'visible';
    document.getElementById('addphone3').style.visibility = 'visible';
    document.getElementById('removephone2').style.visibility = 'visible';
}

function RemovePhone2(){
    document.getElementById('phonerow2').style.visibility = 'hidden';
    document.getElementById('addphone3').style.visibility = 'hidden';
    document.getElementById('removephone2').style.visibility = 'hidden';
    document.getElementById('addphone2').style.visibility = 'visible';
    document.getElementById('phone2').value='';
    document.getElementById('extension2').value='';
    document.getElementById('type2').selectedIndex='0';
}

function AddPhone3(){
    document.getElementById('addphone3').style.visibility = 'hidden';
    document.getElementById('removephone2').style.visibility = 'hidden';
    document.getElementById('phonerow3').style.visibility = 'visible';
}

function RemovePhone3(){
    document.getElementById('phonerow3').style.visibility = 'hidden';
    document.getElementById('addphone3').style.visibility = 'visible';
    document.getElementById('removephone2').style.visibility = 'visible';
    document.getElementById('phone3').value='';
    document.getElementById('extension3').value='';
    document.getElementById('type3').value='';
}