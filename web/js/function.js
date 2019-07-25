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

$("#cities").keyup(() => {
    const cities = $("#cities");
    let value = cities.val().toLowerCase();
    console.log(value);
    let data = {"data" : value};
    $.ajax({
        type: 'POST',
        url: '/ajax/cities',
        data: data,
        success: (success) => {
            let data = JSON.parse(success);
            addElem(data["result"], data["position"]);
        },
        error: (error) => {
            console.log(error);
            $(".hint-response").empty();
        }
    });
});

function addElem(result, pos) {
    const array = [...result];
    const elem = $(".hint-response");
    elem.empty();
    array.forEach((value, index) => {
        console.log(index + "//" + value);
        let span = document.createElement("span");
        span.append(value);
        span.classList.add("id" + index);
        elem.append(span);
        if (index > pos) {
            span.classList.add("colored");
        }
    });

}
