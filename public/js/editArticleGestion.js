console.log("coucou")
let articleName = document.getElementById("name")
let articleDescription = document.getElementById("description")
let articlePrice = document.getElementById("price")
let saveButton = document.getElementById("save")

articleName.addEventListener("input", () => {
    saveButton.style.display = "block";
});
articleDescription.addEventListener("input", () => {
    saveButton.style.display = "block";
});
articlePrice.addEventListener("input", () => {
    saveButton.style.display = "block";
});
function Save(){
    data = {
        name: articleName.textContent ,
        description: articleDescription.textContent,
        price: articlePrice.textContent
    }
    console.log(data)
    fetch("/edit/"+saveButton.dataset.uid,{
        method:"PUT",
        body: JSON.stringify(data)
    })
}
function Delete(button){
    var uid = button.dataset.uid
    var link = window.location.origin
    link+= ("/edit/"+uid)
    console.log(link)
    fetch(link,{
        method:"DELETE"
    })
}