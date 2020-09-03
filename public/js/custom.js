document.addEventListener("DOMContentLoaded", function() {
    const postBtn = document.getElementById('delete-btn');

    postBtn.addEventListener('click', () => {

        const inputPass = document.querySelector('input[name="password"]');

        if(inputPass.value.length === 0) {
            inputPass.classList.add("is-invalid");
        } else {
            inputPass.classList.remove("is-invalid");

            const token = document.querySelector('input[name="_token"]').value;
            const id    = document.querySelector('input[name="id"]').value;
            const data  = '_method=delete&_token=' + token + '&password=' + inputPass.value;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/bookmarks/' + id, true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                const response = JSON.parse(xhr.response);

                if(response.success) {
                    window.location.replace("/");
                } else {
                    inputPass.classList.add("is-invalid");
                    alert(response.message);
                }
            };
            xhr.onerror = function () {
                alert('Error');
            };
            xhr.send(data);
        }

        return false;
    });
});
