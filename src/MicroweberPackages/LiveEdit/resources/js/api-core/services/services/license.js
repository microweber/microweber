import axios from 'axios';

export const License = {

    licenses: null,

    save: async function (licenseKey) {

        return await axios.post(mw.settings.api_url + 'mw_save_license',{
            rel_type:"",
            activate_on_save: 1,
            local_key: licenseKey
        });

    },

}
