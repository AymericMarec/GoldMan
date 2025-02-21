function Search(){
    var search = document.getElementById("search")
    console.log(search)
    window.location.href = window.location.origin+"/article/1?search="+search.value;
}