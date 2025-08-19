// Main Menu
document.querySelector("#btn-main-menu").addEventListener("click", function () {
    document.querySelector("#main-menu").classList.toggle("hidden");
    document.querySelector("#main-content").classList.toggle("hidden");

    const body = document.querySelector("body");

    if (body.classList.contains("bg-gray-800")) {
        body.classList.remove("bg-gray-800");
        body.classList.add("bg-gray-200");
    } else {
        body.classList.remove("bg-gray-200");
        body.classList.add("bg-gray-800");
    }
});

