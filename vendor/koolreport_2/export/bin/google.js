var page = require('webpage').create();
page.open('https://www.kompas.com', function() {
    setTimeout(function() {
        page.render('google.png');
        phantom.exit();
    }, 2000);
});