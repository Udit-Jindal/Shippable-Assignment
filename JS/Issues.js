function submit() {
    var baseUrl = 'Services/Issues.php?url=';
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
        document.getElementById("1").innerHTML  = responseData.total;
        document.getElementById("2").innerHTML  = responseData.last24Hours;
        document.getElementById("3").innerHTML  = responseData.thisWeek;
        document.getElementById("4").innerHTML  = responseData.beforeThisWeek;
    } else {
        alert(responseDescription);
        document.getElementById("1").innerHTML  = '';
        document.getElementById("2").innerHTML  = '';
        document.getElementById("3").innerHTML  = '';
        document.getElementById("4").innerHTML  = '';
    }
}