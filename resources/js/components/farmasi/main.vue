<template>
    <div  >
        
        <router-view v-if="roles.farmasi" ></router-view>
    </div>
</template>


<script>
export default {
    mounted(){
this.$store.dispatch("master");
    },
    computed:{
        roles(){
            let roles =this.$store.getters.get_roles;
            // console.log('roles are '+roles.rwj);
            // console.log(this.$route);
                if(roles.rwj!=undefined){
                    if(!roles.farmasi){
                    // console.log(roles);
                    this.$destroy();
                    this.$router.push('/');this.$toastr.e("Anda Gak Punya Akses ke 'Farmasi'","Gak Ada Akses!");
                }
                    }
            
            return roles?roles:{};
        }
    }
}

</script>
