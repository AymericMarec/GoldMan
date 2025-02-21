console.log("coucou")
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM chargé");
    const articleName = document.getElementById("name");
    const articleDescription = document.getElementById("description");
    const articlePrice = document.getElementById("price");
    const saveButton = document.getElementById("save");

    console.log("Éléments trouvés:", { articleName, articleDescription, articlePrice, saveButton });

    // Vérifier si les éléments existent avant d'ajouter les listeners
    if (articleName && articleDescription && articlePrice && saveButton) {
        articleName.addEventListener("input", () => {
            console.log("Input name détecté");
            saveButton.style.display = "flex";
        });
        
        articleDescription.addEventListener("input", () => {
            console.log("Input description détecté");
            saveButton.style.display = "flex";
        });
        
        articlePrice.addEventListener("input", () => {
            console.log("Input price détecté");
            saveButton.style.display = "flex";
        });
    }
});

function Save() {
    const name = document.getElementById('name').textContent;
    const description = document.getElementById('description').textContent;
    const price = document.getElementById('price').textContent;
    const uid = document.getElementById('save').getAttribute('data-uid');

    fetch(`/edit/${uid}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            name: name,
            description: description,
            price: price
        })
    }).then(response => {
        if(response.ok) {
            document.getElementById('save').style.display = 'none';
            showNotification('Modifications enregistrées avec succès', 'success');
        }
    });
}

function Delete(button) {
    if(confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
        const uid = button.getAttribute('data-uid');
        fetch(`/edit/${uid}`, {
            method: 'DELETE'
        }).then(response => {
            if(response.ok) {
                window.location.href = '/';
            }
        });
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}