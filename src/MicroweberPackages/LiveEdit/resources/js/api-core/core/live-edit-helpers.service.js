export default {
    targetIsIcon: target => {
    
        const iconClasses = ['icon', 'mw-icon', 'material-icons'];
        var isIcon = target.className.includes('mw-micon-');
    
        if(!isIcon) {
            for (let i = 0; i< iconClasses.length; i++) {
                if(target.classList.contains(iconClasses)) {
                    isIcon = true;
                    break;
                }
            }
        }
        return isIcon;
    }
}