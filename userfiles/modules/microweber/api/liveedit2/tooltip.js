export const Tooltip = (node, content, position) => {
    if(!node || !content) return;
    node = node.isMWElement ? node.get(0) : node;
    node.dataset.tooltip = content;
    node.title = content;
    node.dataset.tooltipposition = position || 'top-center';
};


