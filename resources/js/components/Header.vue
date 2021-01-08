<template>
<div>
    <sidebar-menu class="sidenav" id="slide-out" v-bind:hideToggle="true" :menu="menu" width="290px" v-bind:collapsed="false">
        <div slot="header" style="padding:0;margin:0" v-if="user.name">
            <div class="card-panel" style="background-image:url('/images/common/tab_back1.jpg');padding:0px;margin:0">
                <!-- <div class="row valign-wrapper"> -->
                <div class="col row s12">
                    <!-- <div class="col s2"> -->
                    <table>
                        <tr>
                            <td>
                                <img :src="user.photo?'/images/staff/'+user.photo:'/images/staff/'+'default.jpg'" class="circle" style="width:90px;height:90px" />
                            </td>
                            <td>
                                <div class="user-view">
                                    <a>
                                        <span class="white-text name">
                                            {{user.title=='Doktor'?'dr. ':''}} {{user.name}}
                                            <!-- <i class="material-icons">supervised_user_circle</i> -->
                                            <span class="chip white-text teal">{{user.role_name}}</span>
                                        </span>
                                    </a>
                                    <a>
                                        <span class="white-text email">{{user.title}}</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <!-- notice the "circle" class -->
                    <!-- </div> -->
                    <!-- <div class="col s10"> -->

                    <!-- </div> -->
                    <!-- </div> -->
                </div>
            </div>
        </div>
        <div v-if="user.name" slot="footer" class="btn red sidenav-close" @click="logout">Logout</div>
        <div v-else slot="footer" class="btn sidenav-close" @click="redirect('/login')">LogIn</div>
    </sidebar-menu>
    <div class="navbar-fixed animate__animated animate__fadeInDown">
        <nav>
            <div class="nav-wrapper">
            
                <!-- <img src="images/logo-rstb.png" style="width:30px;height:30px" alt="" class="circle responsive-img"> -->
                <a href="#" class="brand-logo right">RSTB</a>
                <a href="#" data-target="slide-out" class="sidenav-trigger show-on-large">
                    <i class="material-icons right">menu</i>
                </a>
                <div v-if="preload==true" class="progress fixed">
                    <div class="indeterminate"></div>
                </div>
                <a v-if="user.name" class="text-float s10">{{user.title=='Doktor'?'dr. ':''}}{{user.name}}</a>
                <span v-if="!user.name&&$route.path!='/login'" @click="login">Login</span>
                

                <!-- Dropdown Structure -->
            </div>
        </nav>

    </div>

</div>
</template>

<style scoped>
.nav-wrapper {
    background: linear-gradient(to right, #ba68c8, purple, purple, #ba68c8);
    /* // purple #43cea2 , #185a9d); */
}
</style>

<script>
export default {
    data() {
        return {
            collpsed: true,

            drop: {
                farmasi: false,
                rwj: false,
                rwi: false,
                home: false,
                oka: false,
            }
        };
    },
    methods: {
        login() {
            if (this.$router.path !== "/login") {
                this.$router.push("/login").catch(error => {});
            }
        },
        logout() {
            this.$store.commit("logout");
            this.$router.push("/");
        },
        redirect(path) {
            this.$router.push(path);
        }
    },

    computed: {
        user() {
            let user = this.$store.getters.currentuser;

            return user ? user : [];
        },
        roles() {
            //this.roles=roles;
            // console.log(roles);
            let roles = this.$store.getters.get_roles;
            return roles ? roles : [];
        },
        menu() {
            let menu = [];
            if (this.roles) {
                menu = [{
                        title: "Home",
                        icon: "fa fa-home",
                        child: [{
                                href: "http://172.100.15.179/sismadak",
                                title: "Simadak",
                                external: true
                            },
                            {
                                class: "sidenav-close",
                                href: "/ppi",
                                title: "P P I"
                            },
                            {
                                class: "sidenav-close",
                                href: "/library",
                                title: "Perpustakan"
                            },
                            {
                                class: "sidenav-close",
                                href: "/laporan",
                                title: "Laporan"
                            },
                            {
                                class: "sidenav-close",
                                href: "/dashboard",
                                title: "Dashboard"
                            }
                        ]
                    },
                    {
                        class: "sidenav-close",
                        title: "Rawat Jalan",
                        icon: "fa fa-user-injured",
                        href: "/rwj/emr",
                        hidden: !this.roles.rwj
                    },
                    {
                        class: "sidenav-close",
                        title: "Jadwal Doktor",
                        icon: "fa fa-clock",
                        href: "/rwj/jadwal",
                        hidden: !this.roles.rwj || !this.roles.head
                    },
                    {
                        class: "sidenav-close",
                        title: "Farmasi",
                        icon: "fa fa-mortar-pestle",
                        href: "/farmasi",
                        hidden: !this.roles.farmasi
                    },
                    {
                        class: "sidenav-close",
                        title: "OKA",
                        icon: "fa fa-highlighter",
                        href: "/oka",
                        hidden: !this.roles.oka
                    },
                    {
                        class: "sidenav-close",
                        title: "Roster",
                        icon: "fa fa-calendar-alt",
                        href: "/roster",
                        hidden: !this.roles.head
                    }
                ];
            }
            return menu ? menu : [];
        },
        preload() {
            // console.log('preloaderl...');
            return this.$store.getters.get_preload;
        },
        overload() {
            return this.$store.getters.get_overload;
        }
    }
};
</script>
