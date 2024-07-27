function search() {
    var searchQuery = document.getElementById("search-input").value;
    window.location.href = "https://www.google.com/search?q=" + encodeURIComponent(searchQuery);
}