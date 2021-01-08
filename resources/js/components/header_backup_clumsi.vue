<ul class="sidenav" id="slide-out">
      <!-- <li><a class="sidenav-close" href="#!">Clicking this will close Sidenav</a></li> -->
      <li v-if="user.name">
        <div class="user-view">
          <div class="background">
            <img src="/images/common/tab_back1.jpg" />
          </div>

          <a>
            <span class="white-text name">
              {{user.name}}
              <i class="material-icons">supervised_user_circle</i>
              <span class="chip white-text teal">{{user.role_name}}</span>
            </span>
          </a>
          <a>
            <span class="white-text email">{{user.email}}</span>
          </a>
        </div>
      </li>

      <li>
        <button @click="drop.home=!drop.home" class="collapsible">Home</button>
        <div v-if="drop.home">
          <ul class="collection">
            <li class="collection-my-item" @click.prevent="redirect('/')">
              <!-- <img src="images/common/home.png" style="width:30px;height:30px" alt="" class="circle responsive-img">  -->
              Home
            </li>

            <li class="collection-my-item" @click.prevent="redirect('/ppi')">
              <!-- <img src="images/common/ppi.png" style="width:30px;height:30px" alt="" class="circle responsive-img">  -->
              P P I
            </li>
            <li class="collection-my-item" @click.prevent="redirect('/library')">
              <!-- <img src="images/common/sismadak_v2.png" alt="" style="width:30px;height:30px" class="circle responsive-img"> -->
              Perpustakan
            </li>
          </ul>
        </div>
        <div class="divider"></div>

        <button v-if="roles.farmasi" @click="drop.farmasi=!drop.farmasi" class="collapsible">Farmasi</button>
        <div v-if="drop.farmasi&&roles.farmasi">
          <ul class="collection">
            <li class="collection-my-item" @click.prevent="redirect('/farmasi')">Resep</li>
            <!-- <li class="collection-my-item" @click.prevent="redirect('/farmasi/stockobat')">
      Stock Obat
            </li>-->
          </ul>
        </div>

        <div class="divider"></div>

        <button v-if="roles.rwj" @click="drop.rwj=!drop.rwj" class="collapsible">Rawat_Jalan</button>
        <div v-if="drop.rwj&&roles.rwj">
          <ul class="collection">
            <li class="collection-my-item" @click.prevent="redirect('/rwj')">List Kunjungan Pasien</li>
            <!-- <li class="collection-my-item" @click.prevent="redirect('/farmasi/stockobat')">
      Stock Obat
            </li>-->
          </ul>
        </div>

        <div class="divider"></div>
      </li>
      <!-- <li><a class="subheader">Account</a></li> -->
      <li v-if="user.name">
        <a class="waves-effect sidenav-close" @click.prevent="logout">Logout</a>
      </li>
      <li v-if="!user.name">
        <a class="waves-effect sidenav-close" @click.prevent="login">Login</a>
      </li>
    </ul>
    <template>
  <div>
    <sidebar-menu class="sidenav" id="slide-out" v-bind:hideToggle=true :menu="menu" v-bind:collapsed=false >

      <div slot="header"   v-if="user.name" style="padding:20px;background-image:url('/images/common/tab_back1.jpg')">
         <div class="user-view">
          

          <a>
            <span class="white-text name">
              {{user.name}}
              <i class="material-icons">supervised_user_circle</i>
              <span class="chip white-text teal">{{user.role_name}}</span>
            </span>
          </a>
          <a>
            <span class="white-text email">{{user.email}}</span>
          </a>
        </div>
      </div>
    </sidebar-menu>
    <div class="navbar-fixed">
      <nav>
        <div class="nav-wrapper">
          <!-- <img src="images/logo-rstb.png" style="width:30px;height:30px" alt="" class="circle responsive-img"> -->
          <a href="#" class="brand-logo right">RSTB</a>
          <a v-if="user.name" class="text-float s10">{{user.name}}</a>
          <span v-if="!user.name&&$route.path!='/login'" @click="login">Login</span>

          <a href="#" data-target="slide-out" class="sidenav-trigger show-on-large">
            <i class="material-icons right">menu</i>
          </a>
          <!-- Dropdown Structure -->
        </div>
      </nav>
    </div>
    <div v-if="preload==true" class="progress">
      <div class="indeterminate"></div>
    </div>
    
  </div>
</template>


<style scoped>
.nav-wrapper {
  background: linear-gradient(to right, #ba68c8, purple, purple, #ba68c8);
  /* // purple #43cea2 , #185a9d); */
}
/* 
.collapsible {
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  background-color: salmon;
  outline: none;
  font-size: 15px;
}
.collection-my-item {
  padding: 5px;
  align-content: center;
  text-align: center;
}
.collection-my-item:hover {
  background-color: gray;
  color: white;
}

.active,
.collapsible:hover {
  background-color: sienna;
  color: white;
}

.collapsible:after {
  content: "\002B";
  color: white;
  font-weight: bold;
  float: right;
  margin-left: 5px;
}

.active:after {
  content: "\2212";
}

.content {
  padding: 0 18px;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
  background-color: #f1f1f1;
} */
</style>
<script>
// var coll = document.getElementsByClassName("collapsible");
// var i;

// for (i = 0; i < coll.length; i++) {
//   coll[i].addEventListener("click", function() {
//     this.classList.toggle("active");
//     var content = this.nextElementSibling;
//     if (content.style.maxHeight) {
//       content.style.maxHeight = null;
//     } else {
//       content.style.maxHeight = content.scrollHeight + "px";
//     }
//   });
// }
// document.addEventListener("DOMContentLoaded", function() {
//   // var elems = document.querySelectorAll('.sidenav');
//   // var instances = M.Sidenav.init(elems, {});
//   //  M.Collapsible.init(document.querySelectorAll('.collapsible'));
//   // var elems = document.querySelectorAll('.collapsible');
//   // var instances = M.Collapsible.init(elems, {});
// });
// document.addEventListener('DOMContentLoaded', function() {

//   });
export default {
  data() {
    return {
      collpsed:true,
      menu: [
                    {
                        header: true,
                        title: '',
                        hiddenOnCollapse: true
                    },
                    {
                        href: '/',
                        title: 'Dashboard',
                        icon: 'fa fa-user'
                    },
                    {
                        
                        title: 'Rawat Jalan',
                        icon: 'fa fa-chart-area',
                        child: [
                            {
                                href: '/charts/sublink',
                                title: 'Sub Link'
                            }
                        ]
                    }
                ],
      drop: {
        farmasi: false,
        rwj: false,
        rwi: false,
        home: false
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
      return this.$store.getters.currentuser
        ? this.$store.getters.currentuser
        : [];
    },
    roles() {
      let roles = {
        farmasi: false,
        rwj: false,
        rwi: false
      };
      // console.log('hello');
      // console.log(this.user);

      if (this.user.id != undefined) {
        if (this.user.role_list == "farmasi" || this.user.role_list == "*") {
          roles.farmasi = true;
        }
        if (this.user.role_list == "rwj" || this.user.role_list == "*") {
          roles.rwj = true;
        }
      }

      //this.roles=roles;
      // console.log(roles);
      return roles;
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