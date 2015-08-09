ajax(_PATH_ + 'test', GET, null, function(ajax){
    document.body.innerHTML = ajax.responseText;
},
function(ajax){
    alert('Error!');
});


