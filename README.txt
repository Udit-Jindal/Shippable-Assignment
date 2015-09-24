# Shippable-Assignment

Front-end is developed in pure HTML and Javascript.(index.html + Issues.js)
To call the GitHub API, a API is made in backend.(Issues.php)

Solution:- 

(Back-end)
To reduce the network time, I am making a single call to get whole data from GitHub. I could have made 4 different calls to GitHub, but that would have increased the time by 4 times.
Once I get the data from GitHub, I am running a loop and calculating all the required counts.

(Front-end)
In front-end a simple HTML page is made. In that a when the button is clicked, it make a Synchronus call to Issues.php and load the data into the table.

Improvements:-

I could have improved the front-end design more. + by using jquery and ajax, the request could have been made asyn.
Secondly, the API design rightnow will work for only open cases and a lot of thing are static as well.
This API could be made more generic.
Also error handling at various points is missing. This should have been handled more efficiently.

The code is hosted on AWS. Below is the URL.

URL:-- http://52.74.46.210/Shippable-Assignment/index.html
