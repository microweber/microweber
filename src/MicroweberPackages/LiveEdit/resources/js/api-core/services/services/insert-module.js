import { ElementManager } from "../../core/classes/element";

const getModule = (module, options = {}) => {
    return axios.post(`${mw.settings.site_url}module`, options)
}

export const insertModule = (target = null, module, options = {}) => {

    if(!target || !module) {
        return;
    }
    return new Promise(async resolve => {
        const data = await getModule(module, options);
        ElementManager(target).before(data);
        resolve()
    });
}