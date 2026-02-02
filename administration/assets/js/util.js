async function handleRequest(action, data, apiEndpoint, formPage) {
    if (action === "delete") {
        const deleteData = confirm("Are you sure you want to delete this data? You will not be able to get it back.");
        console.log(deleteData)
        if (!deleteData) { return; }
    }

    const response = await fetch(apiEndpoint, {
        method: action,
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })

    const result = await response.json();

    console.log(result);

    // Show message
    let msgDiv = document.createElement("div");
    let error = result.message.toLowerCase().includes("error");
    if (!error) {
        msgDiv.className = "alert alert-success";
    } else {
        msgDiv.className = "alert alert-danger";                
    }
    msgDiv.innerText = result.message;
    document.querySelector("form[action='" + formPage + "']").prepend(msgDiv);

    // remove after 3 seconds
    setTimeout(() => {
        msgDiv.remove();
    }, 3000);

    // Optionally, reload or update the table
    if (!error && (action === 'delete' || action === 'post')) {
        setTimeout(() => {window.location.href = './' + formPage;}, 3000);
    }
}

function setupFormListener(formSubmissionPage) {
    const form = document.querySelector("form[action='" + formSubmissionPage + "']");
    if (form) {
        form.addEventListener("submit", async function(e) {
            e.preventDefault();

            let action = "";
            if (form.querySelector("button[name='btnInsert'][type='submit']") && form.querySelector("button[name='btnInsert']").matches(":focus")) {
                action = "post";
            } else if (form.querySelector("button[name='btnUpdate'][type='submit']") && form.querySelector("button[name='btnUpdate']").matches(":focus")) {
                action = "put";
            } else if (form.querySelector("button[name='btnDelete'][type='submit']") && form.querySelector("button[name='btnDelete']").matches(":focus")) {
                action = "delete";
            }

            // fallback: check which button was clicked
            if (!action) {
                const submitter = e.submitter;
                if (submitter && submitter.name) {
                    if (submitter.name === "btnInsert") action = "post";
                    if (submitter.name === "btnUpdate") action = "put";
                    if (submitter.name === "btnDelete") action = "delete";
                }
            }

            handleForm(action);
        });
    }
}