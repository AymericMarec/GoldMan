function Delete(button){
    var uid = button.dataset.uid
    var link = window.location.href
    link+= ("?uid="+uid)
    console.log(link)
    fetch(link,{
        method:"DELETE"
    })
}

function SwitchAdmin(button){
    var uid = button.dataset.uid
    var link = window.location.href
    link+= ("?uid="+uid)
    console.log(link)
    fetch(link,{
        method:"PUT"
    })
}