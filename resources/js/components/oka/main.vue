<template>
    <div  >
        
        <router-view v-if="roles.oka" ></router-view>
    </div>
</template>


<script>
export default {
    mounted(){
    this.$store.dispatch("master");
    this.$store.dispatch("get_apirstb_token");

    },
    computed:{
        roles(){
            let roles =this.$store.getters.get_roles;
            // console.log('roles are '+roles.rwj);
            
                if(roles.oka!=undefined){
                    if(!roles.oka){
                    // console.log(roles);
                    this.$destroy();
                    this.$router.push('/');this.$toastr.e("Anda Gak Punya Akses ke 'OKA'","Gak Ada Akses!");
                }
                    }
            
            return roles?roles:{};
        },
        
    }
}

</script>
