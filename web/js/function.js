window.onload = () => {
    let path = window.location.pathname;
    if (path === "/zboruri") {
        setTimeout(() => {
            history.go(-1);
        }, 4000);
    }
};

function down(event, parent) {
    let posibility = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    let found = posibility.indexOf(event.key) !== -1;

    if (event.key === "Backspace") {
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
        url: '/app_dev.php/ajax/cities',
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
    if (confirm("Sunteți sigur că doriți să anulați toate rezervările/închirierile din coș?")) {
        ajaxDelete();
    }
});

function deleteItem(elem) {
    let type = $(elem).data("type");
    let response = "";
    switch (type) {
        case ("accommodation"):
            response = "rezervare";
            break;
        case ("rental"):
            response = "închiriere";
            break;
    }
    if (confirm("Sunteți sigur că doriți să anulați această " + response + "?")) {
        let id = $(elem).data("id");
        ajaxDelete(type, id);
    }
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
        url: '/app_dev.php/ajax/cart',
        data: data,
        success: (success) => {
            console.log(success);
            let elem = "";
            if (id == null) {
                elem = $("span[data-id]");
            } else {
                elem = $("span[data-id=" + id +"]");
            }
            elem.each(() => {
                let length =  $("span[data-id]").length;
                let container = $(elem).closest("div");
                container[0].nextElementSibling.remove();
                container.remove();
                if (length === 1 || elem.length > 1) {
                    const cart = $("#cart");
                    let p = document.createElement("p");
                    p.innerHTML = "Coșul dumneavoastră este gol!";
                    cart.empty();
                    cart.append(p);
                }
            });
            if (type === "accommodation" || type == null) {
                setTimeout(() => {
                    $("#promo").remove();
                }, 100);
            }
        },
        error: (error) => {
            console.log(error);
            window.alert("A intervenit o eroare, va rugăm încercați mai târziu.");
        },
    });
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

document.body.addEventListener('click', (e) => {
    if (e.target.id === "cart" || $(e.target).parents("#cart").length) {
        //inside
    } else {
        //outside
        document.getElementById("cart").classList.remove("open");
    }

    if (e.target.id === "hint" || $(e.target).parents("#hint").length) {
    } else {
        document.querySelector(".hint-response").classList.remove("hint-open");
    }
}, true);

$("#startDate, #endDate").click(() => {
    const input = $("input#rental_search_location");
    input.focus();
    let intervalId = setInterval(() => {
        console.log("int");
        document.getElementById("rental_search_location").classList.toggle("flash");
    }, 50);
    setTimeout(() => {
        clearInterval(intervalId);
        intervalId = null;
        document.getElementById("rental_search_location").classList.remove("flash");
    }, 500);
});

$("#accommodation_search_startDate").change(() => {
    let start = document.getElementById("accommodation_search_startDate");
    let end = document.getElementById("accommodation_search_endDate");
    let date = new MyDate(start.value);
    date.addDays(1);
    end.setAttribute("min", date.getFormated());
    end.setAttribute("value", date.getFormated());
});

$("#rental_search_startDate").change(() => {
    let start = document.getElementById("rental_search_startDate");
    let end = document.getElementById("rental_search_endDate");
    let date = new MyDate(start.value);
    date.addDays(1);
    end.setAttribute("min", date.getFormated());
    end.setAttribute("value", date.getFormated());
});

$("#accommodation_search_submit").click((e) => {
    let input = document.getElementById("accommodation_search_location");
    let hints = document.querySelectorAll("#hint option");
    let found = false;
    hints.forEach((elem) => {
        if($(elem).attr("id") === input.value) {
            found = true;
        }
    });
    if (found === false) {
        e.preventDefault();
        document.getElementById("hint").classList.add("hint-open");
        input.focus();
        let intervalId = setInterval(() => {
            document.getElementById("accommodation_search_location").classList.toggle("flash");
        }, 50);
        setTimeout(() => {
            clearInterval(intervalId);
            intervalId = null;
            document.getElementById("accommodation_search_location").classList.remove("flash");
        }, 500);

    }
});

class MyDate {
    constructor(date) {
        this.date = new Date(date);
    }
    getFullDate() {
        return this.date;
    }
    getFormated() {
        return this.makeYear() + "-" + this.makeMonth() + "-" + this.makeDay();
    }
    makeYear() {
        let year = this.date.getFullYear();
        return year.toString();
    }

    makeMonth() {
        let month = this.date.getMonth();
        month++;//month starts at 0
        let stringMonth = month.toString();
        if (stringMonth.length === 1) {
            stringMonth = "0" + stringMonth;
        }
        return stringMonth;
    }
    makeDay() {
        let day = this.date.getDate();
        let stringDay = day.toString();
        if (stringDay.length === 1) {
            stringDay = "0" + stringDay;
        }
        return stringDay;
    }
    addDays(days) {
        this.date.setDate(this.date.getDate() + days);
    }
}