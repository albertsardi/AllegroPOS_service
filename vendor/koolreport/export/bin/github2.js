var page = require('webpage').create();
page.open('https://github.com', function() {
    setTimeout(function() {
        page.render('github.png');
        phantom.exit();
    }, 2000);
});