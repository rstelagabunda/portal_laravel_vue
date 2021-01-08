require("./bootstrap");
import Vue from "vue";
import VueRouter from "vue-router";
import Vuex from "vuex";
import { routes } from "./routes";
import Store from "./components/vuex_store/store";
import MainApp from "./components/MainApp.vue";
import "materialize-css/dist/css/materialize.css";
import "materialize-css/dist/js/materialize.js";
import Chartkick from "vue-chartkick";
import Chart from "chart.js";
import moment from "moment";
import TextareaAutosize from "vue-textarea-autosize";
import VueSidebarMenu from 'vue-sidebar-menu'
import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'
import vuetoastr from 'vue-toastr';
import ScrollOut from "scroll-out";
import VueApexCharts from 'vue-apexcharts'
// import Paintable from 'vue-paintable';

Vue.use(vuetoastr);
Vue.use(VueSidebarMenu);
Vue.use(TextareaAutosize);
Vue.use(VueApexCharts);
Vue.component('apexchart', VueApexCharts);
// Vue.use(ScrollOut);

// Vue.use(Paintable, {
//     // optional methods
//     setItem(key, image) {
//       localStorage.setItem(key, image);
//     },
//     // you also can use async
//     getItem(key) {
//       return localStorage.getItem(key);
//     },
//     removeItem(key) {
//       localStorage.removeItem(key);
//     }
//   });

// if ('serviceWorker' in navigator) {
//     window.addEventListener('load', function() {
//       navigator.serviceWorker.register('/sw.js').then(function(registration) {
//         // Registration was successful
//         console.log('ServiceWorker registration successful with scope: ', registration.scope);
//       }, function(err) {
//         // registration failed :(
//         console.log('ServiceWorker registration failed: ', err);
//       });
//     });
//   }
// window.$ = window.jQuery = require("jquery");
// import SortedTablePlugin from "vue-sorted-table";

// Vue.use(SortedTablePlugin);
// Vue.filter("currency", function(value) {
//     return "Rp" + parseFloat(value).toFixed(2);
// });
Vue.filter('currency', function (value) {
    // if (typeof value !== "number") {
    //     return value;
    // }
    var formatter = new Intl.NumberFormat('id-ID', {
        // style: 'currency',
        // currency: '',
        // minimumFractionDigits: 0
    });
    return "Rp " + formatter.format(value);
});
Vue.filter("formatDate", function (value) {
    if (value) {
        // return moment(String(value)).format('MMM Do YYYY')
        return moment(String(value)).format("D-MMM`YY");
    }
});

Vue.filter("date_to_age", function (value) {
    if (value) {
        // return moment(String(value)).format('MMM Do YYYY')
        let fromTime = moment(String(value)).diff(moment(), "milliseconds");
        let duration = moment.duration(fromTime);
        let timeString =
            duration.years() / -1 + "th " + duration.months() / -1 + "bln";

        return timeString; //moment(String(value)).format('D-MMM`YY')
    }
});

// Vue.filter('formatDate', function(value) {
//   if (value) {
//     return moment(String(value)).format('D-MMM`YY HH:mm')
//   }
// });
Vue.filter("capitalize", function (value) {
    if (!value) return "";
    value = value.toString();
    return value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
});

Vue.use(Chartkick.use(Chart));
// import './pdfThumbnails.js';
// import 'pdfjs-dist/build/pdf.js'
Vue.use(VueRouter);
Vue.use(Vuex);
const store = new Vuex.Store(Store);
// Vue.use(chartkick(chart));
const router = new VueRouter({
    mode: "history",
    routes,
    Store
});

// let roles=this.roles;
router.beforeEach((to, from, next) => {
    if (!to.matched.length) {
        next('/notFound');
    } else {
        next();
    }
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const role = to.meta.role;
    // const requiresAuth= meta.requiresAuth;
    // const farmasi= to.matched.some( record => record.meta.requiresAuth);
    const currentuser = Store.state.currentuser;
    const roles = Store.state.roles;
    //console.log(currentuser.role_list);
    if (requiresAuth && !currentuser) {
        next("/login");
    }
    if (requiresAuth) {
        if (currentuser && currentuser.role_list) {
            // console.log(to);
            //router.currentRoute
            let role_list = currentuser.role_list.split(",");
            console.log(role_list);

            // foreach
        } else {
            next();
        }
    }
    if (to.path == "/login" && currentuser) {
        next("/");
    } else {
        next();
    }
});

// Vue.component('modal', {
//     template: '#modal-template'
//   });
Date.prototype.yyyymmdd = function () {
    var mm = this.getMonth() + 1; // getMonth() is zero-based
    var dd = this.getDate();

    return [this.getFullYear(),
    (mm > 9 ? '' : '0') + mm,
    (dd > 9 ? '' : '0') + dd
    ].join('-');
};

document.addEventListener("DOMContentLoaded", function () {
    M.Sidenav.init(document.querySelectorAll(".sidenav"), {});

    M.Modal.init(document.querySelectorAll(".modal"));
    var instance = M.Tabs.init();

    M.ScrollSpy.init(document.querySelectorAll('.scrollspy'), {});
    M.FormSelect.init(document.querySelectorAll("select"), {});
    // M.textareaAutoResize(document.querySelectorAll("textarea"), {});
    M.FloatingActionButton.init(document.querySelectorAll('.fixed-action-btn'));
    M.Carousel.init(document.querySelectorAll('.carousel'), {});
    M.Dropdown.init(document.querySelectorAll('.dropdown-trigger'), {});
    //M.FormSelect.init(elems, options);
});

const app = new Vue({
    el: "#app",
    router,
    store,

    render: h => h(MainApp),
    components: {
        MainApp
    },

    ready() { },
    created() {
        // $(document).ready(function(){
        //     $('.modal').modal();
        //   });
        axios.interceptors.request.use(
            function (config) {
                //console.log(config);
                // Do something before request is sent
                //     console.log('before axios');
                store.dispatch("start_pre_load");
                // if(config.url.includes("https://api.rstelagabunda.com")){
                //     config.headers.common.Authorization="Bearer "+store.master_data.rstb_api_token;
                // }
                return config;
            },
            function (error) {
                // Do something with request error
                store.dispatch("stop_pre_load");
                //console.log(error);
                return Promise.reject(error);
            }
        );

        // Add a response interceptor

        axios.interceptors.response.use(
            response => {
                //console.log('after axios');

                store.dispatch("stop_pre_load");
                return response;
            },
            error => {
                store.dispatch("stop_pre_load");
                // console.log(error);
                if (error.response.status === 401) {
                    this.$toastr.e("Anda Gak Punya Access .. Silahkan coba Kemabli ", "Un-Authorized");
                    store.commit("logout");
                    // console.log(router.currentRoute.path);
                    //if(router.currentRoute)
                    if (router.currentRoute.path != "/login") { router.push("/login"); }
                }
                // return error;
            }
        );
    },
    mounted() {
        // this.so = ScrollOut({
        //     scope: this.$el,
        //     onShown: function(el) {
        //         // remove the class
        //         el.classList.remove("animate__animated");

        //         // force reflow
        //         void el.offsetWidth;

        //         // re-add the animated cl
        //         el.classList.add("animate__animated");
        //       }
        //   });
    },
    destroyed() {
        this.so.teardown();
    },
    computed: {
        currentuser() {
            let cur = Store.state.currentuser;
            return cur ? cur : {};
        },
        roles() {
            console.log(Store.getters.get_roles);
            return Store.state.roles;
        }

    }
});
