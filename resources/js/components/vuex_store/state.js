import { getlocaluser } from "../../helpers/auth";
// const user=null;

const user = getlocaluser();

export function state() {
    return {
        welcomemessage: "Selamat Datang di Portal RS Telaga Bunda",
        currentuser: user,
        isloggedin: !!user,
        loading: false,
        auth_error: null,
        list_lib: [],
        roles:{

        },
        farmasi: {
            list_obat: [],
            list_resep_per_pasien: [],
            list_resep: [],
            stock_obat: []
        },
        load: {
            pre: false,
            over: false
        },
        rwj: {
            list_all: [],
            list_new: [],
            list_payment: [],
            list_close: [],
            list_cur_dok: [],
            list_emr: [],
            emr_cur: {},
            pasien_data: []
        },

        reports_ppi: [],
        master_data: {
            product_type: [],
            doctors: [],
            diag: [],
            proc: [],
            tarif_rj:[],
        rstb_api_token:'',

        }
    };
}
