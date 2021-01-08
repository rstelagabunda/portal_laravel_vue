export function getters() {
    return {
        
        
        // encode(state,input) {
        //     var _keyStr="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        
        //     var output = "";
        //     var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        //     var i = 0;
        //     var string = input;//.replace(/\r\n/g, "\n");
        //     var utftext = "";

        //     for (var n = 0; n < string.length; n++) {

        //         var c = string.charCodeAt(n);

        //         if (c < 128) {
        //             utftext += String.fromCharCode(c);
        //         }
        //         else if ((c > 127) && (c < 2048)) {
        //             utftext += String.fromCharCode((c >> 6) | 192);
        //             utftext += String.fromCharCode((c & 63) | 128);
        //         }
        //         else {
        //             utftext += String.fromCharCode((c >> 12) | 224);
        //             utftext += String.fromCharCode(((c >> 6) & 63) | 128);
        //             utftext += String.fromCharCode((c & 63) | 128);
        //         }

        //     }

        //     input= utftext;
        //     // input = _utf8_encode(input);

        //     while (i < input.length) {

        //         chr1 = input.charCodeAt(i++);
        //         chr2 = input.charCodeAt(i++);
        //         chr3 = input.charCodeAt(i++);

        //         enc1 = chr1 >> 2;
        //         enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
        //         enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
        //         enc4 = chr3 & 63;

        //         if (isNaN(chr2)) {
        //             enc3 = enc4 = 64;
        //         } else if (isNaN(chr3)) {
        //             enc4 = 64;
        //         }

        //         output = output +
        //             _keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
        //             _keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        //     }

        //     return output;
        // },
        // decode(input) {
        //     var _keyStr="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        
        //     var output = "";
        //     var chr1, chr2, chr3;
        //     var enc1, enc2, enc3, enc4;
        //     var i = 0;

        //     input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        //     while (i < input.length) {

        //         enc1 = _keyStr.indexOf(input.charAt(i++));
        //         enc2 = this._keyStr.indexOf(input.charAt(i++));
        //         enc3 = this._keyStr.indexOf(input.charAt(i++));
        //         enc4 = this._keyStr.indexOf(input.charAt(i++));

        //         chr1 = (enc1 << 2) | (enc2 >> 4);
        //         chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
        //         chr3 = ((enc3 & 3) << 6) | enc4;

        //         output = output + String.fromCharCode(chr1);

        //         if (enc3 != 64) {
        //             output = output + String.fromCharCode(chr2);
        //         }
        //         if (enc4 != 64) {
        //             output = output + String.fromCharCode(chr3);
        //         }

        //     }
        //     var utftext = output;
        //     var string = "";
        //     var i = 0;
        //     var c = c1 = c2 = 0;

        //     while (i < utftext.length) {

        //         c = utftext.charCodeAt(i);

        //         if (c < 128) {
        //             string += String.fromCharCode(c);
        //             i++;
        //         }
        //         else if ((c > 191) && (c < 224)) {
        //             c2 = utftext.charCodeAt(i + 1);
        //             string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
        //             i += 2;
        //         }
        //         else {
        //             c2 = utftext.charCodeAt(i + 1);
        //             c3 = utftext.charCodeAt(i + 2);
        //             string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
        //             i += 3;
        //         }

        //     }

        //     output= string;
            

        //     return output;

        // },
        // _utf8_encode(str) {
        //     string = str.replace(/\r\n/g, "\n");
        //     var utftext = "";

        //     for (var n = 0; n < string.length; n++) {

        //         var c = string.charCodeAt(n);

        //         if (c < 128) {
        //             utftext += String.fromCharCode(c);
        //         }
        //         else if ((c > 127) && (c < 2048)) {
        //             utftext += String.fromCharCode((c >> 6) | 192);
        //             utftext += String.fromCharCode((c & 63) | 128);
        //         }
        //         else {
        //             utftext += String.fromCharCode((c >> 12) | 224);
        //             utftext += String.fromCharCode(((c >> 6) & 63) | 128);
        //             utftext += String.fromCharCode((c & 63) | 128);
        //         }

        //     }

        //     return utftext;
        // },

        // private method for UTF-8 decoding
        // _utf8_decode(utftext) {
        //     var string = "";
        //     var i = 0;
        //     var c = c1 = c2 = 0;

        //     while (i < utftext.length) {

        //         c = utftext.charCodeAt(i);

        //         if (c < 128) {
        //             string += String.fromCharCode(c);
        //             i++;
        //         }
        //         else if ((c > 191) && (c < 224)) {
        //             c2 = utftext.charCodeAt(i + 1);
        //             string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
        //             i += 2;
        //         }
        //         else {
        //             c2 = utftext.charCodeAt(i + 1);
        //             c3 = utftext.charCodeAt(i + 2);
        //             string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
        //             i += 3;
        //         }

        //     }

        //     return string;
        // },
        welcomemessage(state) {
            return state.welcomemessage;
        },
        isloading(state) {
            return state.loading;
        },
        isloggedin(state) {
            return state.isloading;
        },
        currentuser(state) {
            return state.currentuser;
        },
        get_roles(state) {
            return state.roles;
        },
        // ppi
        get_reports_ppi(state) {
            return state.reports_ppi;
        },
        // library
        list_lib(state) {
            return state.list_lib;
        },
        // farmasi
        list_resep(state) {
            return state.farmasi.list_resep;
        },
        list_resep_per_pasien(state) {
            return state.farmasi.list_resep_per_pasien;
        },
        list_obat(state) {
            return state.farmasi.list_obat;
        },

        get_stock_obat(state) {
            return state.farmasi.stock_obat;
        },

        // master data

        get_mstr_doctors(state) {
            return state.master_data.doctors;
        },
        mstr_product_type(state) {
            return state.master_data.product_type;
        },
        get_mstr_diag(state) {
            return state.master_data.diag;
        },

        get_mstr_proc(state) {
            return state.master_data.proc;
        },
        get_mstr_tarif_rj(state) {
            return state.master_data.tarif_rj;
        },
        get_mstr_rstb_api_token(state) {
            return state.master_data.rstb_api_token;
        },
        //

        //loaders
        get_preload(state) {
            return state.load.pre;
        },
        get_overload(state) {
            return state.load.over;
        },
        //

        // rwj getters

        get_allvisit(state) {
            return state.rwj.list_all;
        },
        get_rwj_pasiendata(state) {
            return state.rwj.pasien_data[0];
        },
        get_rwj_list_emr(state) {
            return state.rwj;
        }
    };
}
