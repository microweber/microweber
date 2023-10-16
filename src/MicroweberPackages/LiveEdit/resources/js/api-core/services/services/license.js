import axios from 'axios';

export const License = {

    licenses: null,

    saveLicense: async function (licenseKey) {

        await axios.post(route('api.module.list') + '/mw_save_license',{
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
