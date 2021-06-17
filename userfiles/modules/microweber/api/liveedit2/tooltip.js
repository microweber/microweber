export const Tooltip = (node, content, position) => {
    if(!node || !content) return;
    node = node.isMWElement ? node.get(0) : node;
    node.classList.add('tip');
    node.dataset.tip = content;
    node.dataset.tipposition = position || 'top-center';
}
