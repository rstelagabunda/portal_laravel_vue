import { state } from "./state";

export function actions() {
    return {
        login(context) {
            context.commit("login");
        },
        // library
        getListLib(context, name) {
            axios.post("/api/listlib", name, {}).then(response => {
                context.commit("updateListLib", response.data);
            });
        },
        // farmasi
        get_list_obat(context, noreg) {
            axios.post("/api/list_obat", noreg, {}).then(response => {
                context.commit("update_obatlist", response.data.eticket);
            });
        },
        get_allreceipts(context, resep) {
            axios.post("/api/allresep", resep, {}).then(response => {
                context.commit("update_reseplist", response.data);
            });
        },
        get_allreceipts_per_pasien(context, resep) {
            axios.post("/api/allresep_per_pasien", resep, {}).then(response => {
                context.commit(
                    "update_reseplist_per_pasien",
                    response.data.list_resep_per_pasien
                );
            });
        },

        get_allreports_ppi(context) {
            var ppi_data = [
                { name: "IVL", data: {} },
                {
                    name: "ISK",
                    data: {}
                },
                {
                    name: "IDO",
                    data: {}
                }
            ];
            axios.get("/api/get_reports", {}).then(response => {
                //console.log(response.data);

                response.data.list_ppi.forEach(element => {
                    ppi_data[0].data[element.comp] = element.ivl;
                    ppi_data[1].data[element.comp] = element.isk;
                    ppi_data[2].data[element.comp] = element.ido;
                });

                context.commit("update_reports_ppi", ppi_data);
                // M.toast({html: 'Berhasil .. siap untk cetak label ( jika aplikasi e-ticket lagi jalan) !'});
            });
        },
        get_stock_obat(context, product) {
            axios.post("/api/allstock", product, {}).then(response => {
                //M.toast({html: 'Berhasil !'});
                context.commit("update_stock_obat", response.data.list_product);
            });
        },

        //  master data

        get_mstr_doctors(context) {
            axios.get("/api/get_mstr_doctors", {}).then(response => {
                context.commit(
                    "update_mstr_doctors",
                    response.data.list_doctors
                );
            });
        },
        get_mstr_doctors(context) {
            axios.get("/api/get_mstr_doctors", {}).then(response => {
                context.commit(
                    "update_mstr_doctors",
                    response.data.list_doctors
                );
            });
        },
        master_product_type(context, resep) {
            axios.get("/api/master_product_type", {}).then(response => {
                context.commit(
                    "update_master_product_type",
                    response.data.master_product_type
                );
            });
        },
        get_apirstb_token(context){
            axios
            .get(
              "/api/get_token").then(response => {
                context.commit(
                    "update_api_token",
                    response.data.response.token
                );
                  //$store.state.master_data.rstb_api_token=response.data.response.token;
              });
        },
        master(context, resep) {
            axios.get("/api/get_mstr", {}).then(response => {
                context.commit("update_master", response.data);
            });
        },
        get_rstb_api_token(context) {
            axios.get("/api/get_rstb_api_token", {}).then(response => {
                state.rstb_api_token=response.data.token;
            });
        },
        master_proc(context, resep) {
            axios.get("/api/get_mstr_proc", {}).then(response => {
                context.commit("update_master_proc", response.data.master_proc);
            });
        },

        //

        // loaders
        start_pre_load(context) {
            context.commit("update_preload", true);
        },

        stop_pre_load(context) {
            context.commit("update_preload", false);
        },

        start_over_load(context) {
            context.commit("update_overload", true);
        },

        stop_over_load(context) {
            context.commit("update_overload", false);
        },
        //

        // rwj actions

        get_allvisit(context, search) {
            axios.post("/api/get_allvisit", search, {}).then(response => {
                context.commit("update_allvisit", response.data.list_visit);
            });
        },
        get_all_emr(context, search) {
            axios.post("/api/get_all_emr", search, {}).then(response => {
                context.commit("update_all_emr", response.data.list_emr);
            });
        },
        get_pasiendata_noreg(context, noreg) {
            axios
                .post("/api/get_pasiendata_noreg", noreg, {})
                .then(response => {
                    context.commit(
                        "update_rwj_pasiendata",
                        response.data.data_pasien
                    );
                });
        }
    };
}
