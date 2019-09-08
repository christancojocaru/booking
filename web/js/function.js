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

$(".location input").keyup(() => {
    const cities = $(".location input");
    let value = cities.val().toLowerCase();

    if (value === "") {
        document.getElementsByClassName("hint-response")[0].classList.remove("hint-open");
    }

    let data = {"data" : value};
    let hint_response = $("datalist.hint-response");
    $.ajax({
        type: 'POST',
        url: '/ajax/cities',
        data: data,
        success: (success) => {
            console.log(success);
            let data = JSON.parse(success);
            hint_response.empty();
            $.each(data, (index, city) => {
                addElem(city["result"], city["position"]);
            });
            document.getElementsByClassName("hint-response")[0].classList.add("hint-open");
        },
        error: (error) => {
            console.log(error);
            hint_response.empty();
        }
    });
});

$("#cart-delete").click(() => {
    ajaxDelete();
});

function deleteItem(elem) {
    let type = $(elem).data("type");
    let id = $(elem).data("id");
    ajaxDelete(type, id);
}

function ajaxDelete(type = null, id = null) {
    let user_id = $("#cart-delete").data("user-id");
    let data = {
        user : user_id,
        type : type,
        id   : id
    };

    $.ajax({
        type: 'POST',
        url: '/ajax/cart',
        data: data,
        dataType: "json",
        success: (success) => {
            let data = JSON.parse(success);
            window.alert("Din coșul dumneavoastră au fost șterse "
                + data.accommodations + " rezervări și "
                + data.rentals + " închirieri");

        },
        error: (error) => {
            console.log(error);
            window.alert("A intervenit o eroare, va rugăm încercați mai târziu.");
        },
    });
}

function closeTag(elem) {
    let type = $(elem).data("type");
    let id = $(elem).data("id");

}

function addElem(result, pos) {
    const array = [...result];
    const hint_response = $("datalist.hint-response");
    let option = document.createElement("option");
    option.id = result;
    array.forEach((value, index) => {
        let span = document.createElement("span");
        span.append(value);
        option.append(span);
        if (index > pos) {
            span.classList.add("color");
        }
    });
    option.addEventListener("mouseover", () => {
        let datas = option.getElementsByClassName("color");
        $.each(datas, (index, value) => {value.classList.add("colored");}
        );
    });
    option.addEventListener("mouseout", () => {
        let datas = option.getElementsByClassName("color");
        $.each(datas, (index, value) => {value.classList.remove("colored");}
        );
    });
    option.addEventListener("click", () => {
        document.querySelector(".hint input").value = option.id;
    });
    hint_response.append(option);
}

$("#username").click(() => {
    document.getElementById("cart").classList.add("open");
});

document.querySelector(".hint input").addEventListener("click", () => {
    document.querySelector(".hint-response").classList.add("hint-open");
});

document.body.addEventListener('click', () => {
    document.getElementById("cart").classList.remove("open");
    document.querySelector(".hint-response").classList.remove("hint-open");
}, true);