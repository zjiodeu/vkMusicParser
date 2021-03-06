/*
 * it must run from phantomjs
 * args[0] - remixsid cookie value
 * args[1] - target vk url
 */
var sys = require("system"),
    page = require("webpage").create(),
    post = phantom.args[2] ? 'q=' + phantom.args[2] : false;
    
phantom.addCookie({
    'name': 'remixsid',
    'value': phantom.args[0],
    'domain': 'vk.com'
});

page.settings = {
	loadImages: false,
	javascriptEnabled: true,
	userAgent: 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/22.0.1207.1 Safari/537.1',
};

function vkHandler() {
    var tries = 0,
    t = setInterval(function() {
        page.scrollPosition = { top: page.scrollPosition.top + screen.height, left: 0 };
        ++tries;
            if (tries === 40) {            
                console.log(page.content);
                clearInterval(t);
                phantom.exit();           
            }
    },100);
}
if (post) {
    page.open(phantom.args[1], 'post', post, vkHandler);
}
else {
    page.open(phantom.args[1], vkHandler);
}
    

