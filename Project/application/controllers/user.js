$(".access").click(function(event){
    var me = event.target;
    var jme = $(me);
    var permid = jme.data("id");
    var active = true;
    if(getAccessType(me) == "all") {
        active = false;
    }
    var res = JSON.parse(download(managerurl + "/editpermission/" + uid + "/" + permid + "/" + active));
    if(typeof res.error !== 'undefined'){
        alert(res.error);
        return;
    }
    if(res.active){
        setAccessType(me, 'all');
    }
    else{
        if(res.inheriting){
            setAccessType(me, 'inherit');
        }
        else{
            setAccessType(me, 'none');
        }
    }
    
    for(var i = 0; i < res.inherit.length; i++){
        var id = res.inherit[i];
        var ele = $('*[data-id="' + id + '"]');
        if(ele.length > 0) ele = ele[0]; else continue;
        if(res.active) setAccessType(ele, 'inherit'); else setAccessType(ele, 'none');
    }
});

function setAccessType(element, type){
    switch(getAccessType(element)){
        default:
        case 'all':
            element.classList.remove('all');
            break;
        case 'inherit':
            element.classList.remove('inherit');
            break;
        case 'none':
            element.classList.remove('none');
            break;
    }            
    element.classList.add(type);
}

function getAccessType(element){
    if(element.classList.contains("all")) {
        return "all";
    }
    else if(element.classList.contains("inherit")){
        return "inherit";
    }
    else if(element.classList.contains("none")){
        return "none";
    }
    else return 'all';
}

function download(url){
    var uri = url;
    var data = null;
    $.ajax({
        url: uri,
        success: function (result) {
            data = result;
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            alert("Status: " + textStatus); alert("Error: " + errorThrown); 
        },  
        async: false
    });
    return data;
}
