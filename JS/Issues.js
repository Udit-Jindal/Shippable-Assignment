function submit() {
    var baseUrl = 'http://172.16.7.59/app/Services/Issues.php?url=';
    var url = document.getElementById("URL");
    var finalUrl = baseUrl + url.value.toString();
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", finalUrl, false); // false for synchronous request
    xmlHttp.send(null);
    var responseArray = JSON.parse(xmlHttp.responseText);
    var responseCode = responseArray.code;
    var responseDescription = responseArray.description;
    var responseData = responseArray.data;
    if (responseCode == 1) {
        document.getElementById("1").value = responseData.total;
        document.getElementById("2").value = responseData.last24Hours;
        document.getElementById("3").value = responseData.thisWeek;
        document.getElementById("4").value = responseData.beforeThisWeek;
        document.getElementById("table").style.display = true;
    } else {
        document.getElementById("label").value = responseDescription;
    }
}