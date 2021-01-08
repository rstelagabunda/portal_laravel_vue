import Home from "./components/Home.vue";
import receiptlist from "./components/farmasi/receiptlist.vue";
import receipt from "./components/farmasi/receipt_ind.vue";
import resetobat from "./components/farmasi/resetobat.vue";
import farmasimain from "./components/farmasi/main.vue";
import library from "./components/library/viewall_lib.vue";
import laporan from "./components/laporan/laporan.vue";
import login from "./components/auth/login.vue";
import ppi from "./components/ppi/main.vue";
import emr from "./components/rwj/emr_rj.vue";
import view_all from "./components/rwj/view_all.vue";
import rwjmain from "./components/rwj/main.vue";
import logout from "./components/logout.vue";
import okamain from "./components/oka/main.vue";
import oka from "./components/oka/new.vue";
import roster from "./components/roster";
import jadwal from "./components/rwj/jadwal.vue";
import dashboard from "./components/dashboard/main.vue";
import notfound from "./components/404.vue";
// import { mapGetters } from 'vuex'

function guardMyroute(to, from, next) {
    // mapGetters({
      
    //     user:"currentuser",
      
       
    // });
    // // let user = store.getters['currentuser'];
    // console.log(this.user);
    // if (!(user) && to.path != '/login') {
    //   next('/login');
    // }
    // else if ((user)) {
    //   if (user.role=='*' || user.role==to.meta.role ){// || !(to.name == store.getters['auth/user'].GroupUser + '.home')) {
    //     next();
    //   }
    //   else {
    //     alert("Anda Gak Punya Akses ke  -->  "+to.meta.role,"Gak Ada Akses!");
    //     next('/');
    //   }
    // }
    // else {
    //   next();
    // }
    next();
  };
export const routes = [
    {
        path: "/",
        component: Home
    },
    {
        path: "/notFound",
        component: notfound
    },
    {
        path: "/login",
        component: login
    },
    {
        path: "/ppi",
        component: ppi
    },
    {
        path: "/library",
        component: library
    },
    {
        path: "/laporan",
        component: laporan
    },
    {
        path: "/dashboard",
        component: dashboard
    },
    {
        path: "/farmasi",
        component: farmasimain,
        meta: {
            requiresAuth: true,
            role: "farmasi"
        },
        children: [
            {
                path: "/",
                component: receiptlist,
                meta: {
                    onlyhead:false,
                    role: "farmasi"
                },
                beforeEnter: guardMyroute,
            },
            {
                path: "resep/:id",
                component: receipt,
                meta: {
                    onlyhead:false,
                    role: "farmasi"
                },
                beforeEnter: guardMyroute,
            },
            {
                path: "stockobat",
                component: resetobat,
                meta: {
                    role: "adm_farmasi",
                    onlyhead:true,
                },
                beforeEnter: guardMyroute,
            }
        ]
    },
    {
        path: "/logout",
        component: logout
    },
    {
        path: "/oka",
        component: okamain,
        children: [
            {
                path: "/",
                component: oka,
                meta: {
                    onlyhead:true,
                    role: "oka"
                },
                beforeEnter: guardMyroute,
            }],
        meta: {
            requiresAuth: true,
            role: "oka"
        }
    },
    {
        path: "/roster",
        component: roster,
        meta: {
            requiresAuth: true,
            onlyhead:true
        },
        beforeEnter: guardMyroute,
    },
    {
        path: "/rwj",
        component: rwjmain,
        meta: {
            requiresAuth: true,
            role: "rwj"
        },
        children: [
            {
                path: "/",
                component: view_all,
                meta: {
                    onlyhead:false,
                    role: "rwj"
                },
                beforeEnter: guardMyroute,
            },
            {
                path: "emr",
                component: emr,
                meta: {
                    onlyhead:false,
                    role: "emr"
                },
                beforeEnter: guardMyroute,
            },
            {
                path: "jadwal",
                component: jadwal,
                meta: {
                    onlyhead:true,
                    role: "jadwal"
                },
                beforeEnter: guardMyroute,
            },
            {
                path: "emr/:id",
                component: emr,
                meta: {
                    onlyhead:false,
                    role: "emr"
                },
                beforeEnter: guardMyroute,
            }
        ]
    }
];
