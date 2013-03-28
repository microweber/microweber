(function() {
    var files = [
        'util.js',
        'tpl.js',
        'node.js',
        'leaf.js'
    ];
    var tags = [];
    var host = '/src/';
    _.each(files, function(file){
        tags.push('<script src="'+ host + file + '"></script>'); 
    });
    document.write(tags.join(''));
})();