const renderBgVideos = function() {

    function rend(root) {
        Array.from(root.querySelectorAll('[data-mwvideo]')).forEach(function(node){
            var url = node.dataset.mwvideo.trim();
            node.innerHTML = `<video src="${url}" autoplay muted></video>`;
        });
    }

    rend(document);

    const frame = document.querySelector('#live-editor-frame');
    if(frame && frame.contentDocument) {
        rend(frame.contentDocument);
    }

}

renderBgVideos();

addEventListener('DOMContentLoaded', e => renderBgVideos());
addEventListener('load', e => renderBgVideos());
