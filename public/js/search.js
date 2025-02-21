let search = document.getElementById("search")

function Search(){
    window.location.href = window.location.origin+"/article/1?search="+search.value;
}