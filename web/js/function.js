function down(event, parent) {
    let posibility = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    let found = posibility.indexOf(event.key) !== -1;

    if (event.key === "Backspace") {
        console.log("here");
        parent.value = "";
        parent.data = false;
    }

    if (found && !parent.data) {
        setTimeout(() => {
            if (parent.value !== "") {
                parent.value = parent.value + " persoane";
                parent.data = false;
            }
        }, 1000);
        parent.value = "";
        parent.data = true;
    }

    if (!found) {
        event.preventDefault();
        parent.data = false;
    }
}