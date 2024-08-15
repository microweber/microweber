import { FreeDraggableElementManagerTools } from "./free-draggable-element-manager";

export class FreeElementActions {


    static zIndex(target, value = null) {
        if(value === null) {
            let zIndex = getComputedStyle(target).zIndex;
            if(zIndex === 'auto') {
                zIndex = 0;
            }
            return parseFloat(zIndex);
        }
        mw.top().app.cssEditor.temp(target, 'z-index', value);
    }

    static zIndexMinimumAvailable(target) {
        let min = Number.MAX_VALUE;
        const nodes = FreeDraggableElementManagerTools.getNeighbours(target);
        console .log(nodes, target);
        nodes.forEach(function (item) {
            if (item !== target ) {
                const zIndex = FreeElementActions.zIndex(item);
                min = Math.min(min, zIndex);
            }
        });
        return min;
    }

    static zIndexMaximumAvailable(target) {
        let max = Number.MIN_VALUE;
        const nodes = FreeDraggableElementManagerTools.getNeighbours(target);
        nodes.forEach(function (item) {
            if (item !== target ) {
                const zIndex = FreeElementActions.zIndex(item);
                max = Math.max(max, zIndex);
            }
        });
        return max;
    }

    static zIndexIncrement(target) {
        const curr = FreeElementActions.zIndex(target)
        FreeElementActions.zIndex(target, Math.min(FreeElementActions.zIndexMaximumAvailable(target), curr) + 1);
    }


    static zIndexDecrement(target) {
        const curr = FreeElementActions.zIndex(target)
        FreeElementActions.zIndex(target,Math.min(FreeElementActions.zIndexMaximumAvailable(target), curr) - 1);
    }

    static zIndexMax(target) {
        let max = 0;
        const nodes = FreeDraggableElementManagerTools.getNeighbours(target);
        nodes.forEach(function (item) {
            if (item !== target ) {
                const zIndex = FreeElementActions.zIndex(item);
                max = Math.max(max, zIndex);
            }
        });
        max = max + 1;
        FreeElementActions.zIndex(target, max);
        return max;
    }


    static zIndexMin(target) {
        let min = 0;
        const nodes = FreeDraggableElementManagerTools.getNeighbours(target);
        nodes.forEach(function (item) {
            if (item !== target ) {
                const zIndex = FreeElementActions.zIndex(item);
                min = Math.min(min, zIndex);
            }
        });
        min = min - 1;
        FreeElementActions.zIndex(target, min);
        return min;
    }
}
