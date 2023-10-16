import axios from 'axios';

export const License = {

    licenses: null,

    save: async function (licenseKey) {

        await axios.post(mw.settings.api_url + 'mw_save_license',{
            rel_type:"",
            activate_on_save: 1,
            local_key: licenseKey
        })
            .then((response) => {
                console.log(response);
            });

        return {
            error: true,
            message: 'License is not valid'
        };
    },

}
