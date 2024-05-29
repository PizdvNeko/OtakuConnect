function addLike(idPost, idUser) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        post = document.getElementById(idPost);
        post.innerHTML = this.responseText;
        if(post.getAttribute("class") == "bi bi-heart"){
            post.setAttribute("class", "bi bi-heart-fill");
        } else {
            post.setAttribute("class", "bi bi-heart");
        }
        }
    };
    xmlhttp.open("GET", "insertLike.php?iduser=" + idUser + "&idpost=" + idPost, true);
    xmlhttp.send();
}

function addExpandText(){
    document.addEventListener("DOMContentLoaded", function() {
        let elements = document.getElementsByClassName("text");
        for (let i = 0; i < elements.length; i++) {
            let element = elements[i];
            element.innerHTML += '<span class="expand-text">click to expand</span>'; // Add expand text
            let expandText = element.querySelector('.expand-text');

            // Check if content overflows
            if (element.scrollHeight > element.clientHeight) {
            expandText.style.display = 'block'; // Display expand text
            } else {
            expandText.style.display = 'none'; // Hide expand text if not needed
            }

            expandText.addEventListener('click', function() {
            if (element.classList.contains('expanded')) {
                // Collapse the element
                element.style.height = '5em';
                element.classList.remove('expanded');
            } else {
                // Expand the element
                element.style.height = 'auto';
                element.classList.add('expanded');
            }
            });
        }
    });
}

