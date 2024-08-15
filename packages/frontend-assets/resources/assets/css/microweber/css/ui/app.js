const less = require('less');
const fs = require('fs');
const x = fs.readFileSync('assets/ui.less').toString();


less.render(x)
    .then(function(output) {
            fs.writeFile('../ui.css', output.css, function (err) {
                if (err) throw err;
                console.log('Changes saved');
            });
    },
    function(error) {
		console.log(error);
    });
