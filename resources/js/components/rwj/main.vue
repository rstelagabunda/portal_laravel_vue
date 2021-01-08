<template>
<div>

    <router-view v-if="roles.rwj"></router-view>
</div>
</template>

<script>
export default {
    mounted() {
        this.$store.dispatch("master");
        this.$store.dispatch("get_apirstb_token");

    },
    computed: {
        roles() {
            let roles = this.$store.getters.get_roles;
            //console.log('roles are '+roles.rwj);

            if (roles.rwj != undefined) {
                if (!roles.rwj) {
                    // console.log(roles);
                    this.$destroy();
                    this.$router.push('/');
                    this.$toastr.e("Anda Gak Punya Akses ke 'Rawat Jalan'", "Gak Ada Akses!");
                }
            }

            return roles ? roles : {};
        },

    }
}
</script>

<style>
*,
*:before,
*:after {
    box-sizing: border-box;
    outline: none;
}

table {
    border-collapse: separate;
}

td {
    border: solid 1px #000;
}

tr:first-child td:first-child {
    border-top-left-radius: 10px;
}

tr:first-child td:last-child {
    border-top-right-radius: 10px;
}

tr:last-child td:first-child {
    border-bottom-left-radius: 10px;
}

tr:last-child td:last-child {
    border-bottom-right-radius: 10px;
}

.selsai {
    margin: 10px;
    border: 1px solid transparent;
    border-radius: 10px;
    background: rgb(238, 174, 202);
    background: radial-gradient(circle,
            rgba(238, 174, 202, 1) 0%,
            rgba(148, 187, 233, 1) 100%);
}

.tabs {
    position: relative;

    background-color: #fff;
}

.tabs-triggers {
    display: flex;
}

.tabs-trigger {
    /* flex: 1 0 auto; */
    margin: 0;
    padding: 1rem;
    background-color: #fff;
    font-weight: bold;
    border-bottom: 3px solid white;

    transition: 100ms linear all;
    cursor: pointer;
    /* height:100px; */
}

.tabs-trigger--active {
    /* background-color: gray; */

    color: darkcyan;
    /* border-bottom: 2px solid darkcyan; */
    border: 1px solid white;
    border-bottom: 3px solid darkcyan;
    transition: 100ms linear all;
    margin-bottom: 1px;
    /* border-bottom-color: darkcyan; */
    /* text-decoration: underline; */

    /* border-radius: 10px; */
}

.tabs-content {
    padding: 1rem;
    background-color: #fff;
}
</style>
