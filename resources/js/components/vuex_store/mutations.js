export function mutations() {
    return {
        login(state) {
            state.loading = true;
            state.auth_error = null;
        },
        roles(state,payload){
            console.log('saved to vuex roles');
            state.roles=payload;
        },
        loginsuccess(state, payload) {
            state.auth_error = null;
            state.isloggedin = true;
            state.loading = false;
            state.currentuser = Object.assign({}, payload.user, {
                token: payload.access_token
            });
            localStorage.setItem("user", JSON.stringify(state.currentuser));
        },
        loginfailed(state, payload) {
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state) {
            localStorage.removeItem("user");
            state.isloggedin = false;
            state.currentuser = null;
            state.roles={};
        },
        updateListLib(state, payload) {
            state.list_lib = payload;
        },
        update_obatlist(state, payload) {
            state.farmasi.list_obat = payload;
        },
        update_reseplist(state, payload) {
            state.farmasi.list_resep = payload;
        },
        update_reseplist_per_pasien(state, payload) {
            state.farmasi.list_resep_per_pasien = payload;
        },

        update_reports_ppi(state, payload) {
            state.reports_ppi = payload;
        },
        update_stock_obat(state, payload) {
            state.farmasi.stock_obat = payload;
        },

        // master data

        update_mstr_doctors(state, payload) {
            state.master_data.doctors = payload;
        },
        update_master_product_type(state, payload) {
            state.master_data.product_type = payload;
        },
        update_master(state, payload) {
            state.master_data.diag = payload.diag;
            state.master_data.doctors = payload.doctors;
            state.master_data.product_type = payload.product_type;
            state.master_data.proc = payload.proc;
            state.master_data.tarif_rj=payload.tarif_rj;
            //state.master_data.rstb_api_token=payload.rstb_api_token;



        },
        update_api_token(state,payload){
            state.master_data.rstb_api_token=payload;
        },
        update_master_proc(state, payload) {
            state.master_data.proc = payload;
        },

        //
        // loaders

        update_preload(state, payload) {
            state.load.pre = payload;
        },
        update_overload(state, payload) {
            state.load.over = payload;
        },
        //

        // rwj mutations

        update_allvisit(state, payload) {
            state.rwj.list_all = payload;
        },
        update_rwj_pasiendata(state, payload) {
            state.rwj.pasien_data = payload;
        },
        update_all_emr(state, payload) {
            state.rwj.list_emr = payload;
        }
    };
}
