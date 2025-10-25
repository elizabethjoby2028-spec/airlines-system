document.getElementById("searchForm").addEventListener("submit", function(e){
    e.preventDefault();
    let s = document.getElementById("source").value.trim();
    let d = document.getElementById("destination").value.trim();
    fetch("search.php?source=" + encodeURIComponent(s) + "&destination=" + encodeURIComponent(d))
    .then(response => response.text())
    .then(data => document.getElementById("results").innerHTML = data)
    .catch(err => console.log(err));
});
