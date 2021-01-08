<template>
  <div>
    <!-- <div class="tabs1 effect-4">
		
			<input type="radio" id="tab-1" name="tab-effect-4" checked="checked">
			<span>
				<i class="fa fa-home"></i><span>Home</span>
			</span>

			<input type="radio" id="tab-2" name="tab-effect-4">
			<span>
				<i class="fa fa-calendar"></i><span>Calendar</span>
			</span>

			

			
			
    </div>-->

    <!-- modal for rwj, rwi pasien list begins-->
    <div v-if="(activepasien.rwj.show || activepasien.rwi.show)" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">
          <div class="chip right" @click="activepasien.rwj.show=false;activepasien.rwi.show=false">X</div>
          <div class="modal-header">
            <slot name="header">Rawat {{activepasien.rwj.show?'Jalan':'Inap'}}</slot>
          </div>

          <div class="modal-body">
            <input
              type="text"
              v-if="activepasien.rwj.show"
              v-model="activepasien.rwj.search"
              placeholder="Cari Pasien Rawat Jalan"
            />
            <input
              type="text"
              v-if="activepasien.rwi.show"
              v-model="activepasien.rwi.search"
              placeholder="Cari Pasien Rawat Inap"
            />

            <div name="body">
              <!-- <table class="striped highlight"  >
                <thead>
                  <tr>
                    <th>Nama Pasien</th>
                    <th>No MR</th>
                    <th>Tanggal Rawat</th>
                  </tr>
                </thead>
                <tbody style="max-height:350px;overflow-y:auto" >
                  <tr
                    v-for="(pasien,inde) in (activepasien.rwj.show?rwj_search:rwi_search)"
                    :key="inde"
                    @click="get_reciepts_pasien(pasien)"
                  >
                    <td>{{pasien.PatientName | capitalize}}</td>
                    <td>{{pasien.NoMR}}</td>
                    <td>{{(activepasien.rwj.show?pasien.TglKunjungan:pasien.StartDate) | formatDate}}</td>
                  </tr>
                </tbody>
               
              </table>-->
              <ul style="height:350px;overflow-y:auto">
                <li>
                  <table class="striped highlight">
                    <thead>
                      <tr>
                        <th>Nama Pasien</th>
                        <th>No MR</th>
                        <th>Tanggal Rawat</th>
                      </tr>
                    </thead>
                    <tbody style="max-height:350px;overflow-y:auto">
                      <tr
                        v-for="(pasien,inde) in (activepasien.rwj.show?rwj_search:rwi_search)"
                        :key="inde"
                        @click="active=(activepasien.rwj.show?0:1);get_reciepts_pasien({'NoRegistrasi':(activepasien.rwj.show?pasien.NoRegistrasi:pasien.NoRegRI),'NoResep':(activepasien.rwj.show?pasien.NoRegistrasi:pasien.NoRegRI)+'-00','First Name':''})"
                      >
                        <td>{{pasien.PatientName | capitalize}}</td>
                        <td>{{pasien.NoMR}}</td>
                        <td>{{(activepasien.rwj.show?pasien.TglKunjungan:pasien.StartDate) | formatDate}}</td>
                      </tr>
                    </tbody>
                  </table>
                </li>
                <!-- <li
                  v-for="(pasien,ind) in activepasien.rwj.show?rwj_search:rwi_search"
                  :key="ind"
                  @click="get_reciepts_pasien(pasien)"
                  
                >
                  <div v-if="activepasien.rwj.show">
                    <span>{{pasien.NoMR}} - {{pasien.PatientName}} - {{pasien.TglKunjungan | formatDate}}</span>
                  </div>
                  <div v-else>
                    <span>{{pasien.NoMR}} - {{pasien.PatientName}} - {{pasien.StartDate | formatDate}}</span>
                  </div>
                </li>-->
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- modal for rwj, rwi pasien list ends-->

    <div class="container" name="reseplist" v-if="show.list_resep&&!show.resep">
      <div class="col s12">
        <div class="tabs col s10" id="tabs" style="height:70px">
          <div class="tabs-triggers">
            <div
              v-for="(item, index) in categories"
              :class="[index === active ? 'tabs-trigger tabs-trigger--active' : 'tabs-trigger']"
              :key="index"
              @click="activate(item.ind);get_reciepts()"
            >
              <!-- <i class="material-icons">{{item.icon}}</i>
              -->
              <i :class="item.icon">
                <span v-if="item.notif==true" class="notification-number">{{notification_list.length}}</span>

  </i><span class="hide-on-small-only show-on-medium-and-up">{{item.name}}</span>
              
              <input
                v-if="index == active"
                type="checkbox"
                class="dd-input"
                id="test"
              />
            </div>
            
  
          </div>
        </div>
        <div class="col s2">
          <label class="dropdown">
            <div class="dd-button" @click="get_list_rwj_rwi()">
              <i class="fa fa-capsules"></i>
               Penjualan
            </div>

            <input type="checkbox" class="dd-input" id="test" />

            <ul class="dd-menu">
              <li @click="activepasien.rwj.show=true">
                <i class="fa fa-user-injured"></i> Resep Rawat Jalan
              </li>
              <li @click="activepasien.rwi.show=true">
                <i class="fa fa-bed"></i> Resep Rawat Inap
              </li>
              <li class="divider"></li>
              <li>
                <i class="fa fa-walking"></i> Penjualan Bebas
              </li>
              <li>
                <i class="fa fa-capsules"></i> Pengeluaran BHP
              </li>
            </ul>
          </label>
        </div>
      </div>
      <!-- drop down begins -->

      <!-- dro dwon ends -->
      <div>
        <div class="tabs-content">
          <div class="row">
            <!-- <i class="material-icons">{{categories[active].icon}}</i> -->
            <div class="input-field col s12" style="margin-bottom:1px">
              <input
                autocomplete="off"
                v-model="nama_search"
                v-on:keyup.enter="get_reciepts()"
                id="first_name"
                type="text"
                class="validate"
              />
              <label for="first_name">Cari Pasien / no MR</label>
            </div>
          </div>
          <table class="striped highlight" style="max-height: 400px;
height:auto !important;
height:400px;">
            <thead>
              <tr>
                <th v-if="active<=2">Nama Pasien</th>
                <th>Tgl Resep</th>
                <th>No Resep</th>
                <th>No MR</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody v-if="receipts_rj_store" style="max-height:350px;overflow-y:auto">
              <tr v-for="(res,inde) in receipts_rj" :key="inde" @click="get_reciepts_pasien(res)">
                <td v-if="active<=2">{{res.PatientName | capitalize}}</td>
                <td>{{res.tglresep | formatDate}}</td>
                <td>{{sub(res.NoResep)}}</td>
                <td>{{res.NoMR}}</td>
                <td>
                  {{res['Status Name']}}
                  <span class="badge white">{{res['First Name']}}</span>
                </td>
              </tr>
            </tbody>
            <tr v-if="!receipts_rj">
              <td></td>
              <td colspan="4">-- tidak ada Pasien --</td>
              <td></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <!-- <router-link to="/farmasi">
      <i class="material-icons">arrow_back</i> back to list resep
    </router-link>-->
    
  </div>
</template>
<script>
import store from "../vuex_store/store";
export default {
  data() {
    return {
      show: {
        list_resep: true,
        resep: false,
      },
      activepasien: {
        show: false,
        rwj: {
          show: false,
          data: false,
          search: "",
        },
        rwi: {
          show: false,
          data: false,
          search: "",
        },
      },
     
      active: 0,
      nama_search: "",
     receipts_rj:[],
      show_modal: true,
      notification_list:[],
      categories: [
        { ind: 0, name: "Rawat Jalan", icon: "fa fa-user-injured", notif:false },
        { ind: 1, name: "Rawat Inap", icon: "fa fa-procedures", notif:false },
        { ind: 2, name: "Bebas", icon: "fa fa-walking", notif:false },
        { ind: 3, name: "Pending", icon: "fa fa-bell", notif:true },

      ],
      
    };
  },
  beforeRouteEnter(to, from, next) {
    // store.dispatch("master");
    console.log("enter");
    next();
  },
  // when route changes and this component is already rendered,
  // the logic will be slightly different.
  beforeRouteUpdate(to, from, next) {
    console.log("update");
    next();
  },

  mounted() {
    // this.$store.dispatch("master");
    this.$store.dispatch("get_allreceipts", {
      nama: this.nama_search,
      type: this.active == 0 ? "rj" : "ri",
    });
    this.get_notification_list();
    // this.$store.dispatch("get_allreceipts_per_pasien", {
    //   noreg: this.$route.params.id
    // });
  },
  computed: {
    // reseps() {
    //   let list = this.$store.getters.list_resep_per_pasien;
    //   if (list.length > 0) {
    //     this.get_obat(list[0].NoResep);
    //   }
    //   return list;
    // },

    rwj_search() {
      if (this.activepasien.rwj.data.length > 0) {
        let st = this.activepasien.rwj.search;
        let result = [];
        if (this.activepasien.rwj.search == 0) {
          return this.activepasien.rwj.data;
        }
        this.activepasien.rwj.data.forEach((element) => {
          // console.log(element.PatientName);
          if (
            element.PatientName.toString()
              .toLowerCase()
              .indexOf(st.toLowerCase()) > -1 ||
            element.NoMR.toString().toLowerCase().indexOf(st.toLowerCase()) > -1
          ) {
            result.push(element);
          }
        });
        return result;
      }
    },

    rwi_search() {
      if (this.activepasien.rwi.data.length > 0) {
        let st = this.activepasien.rwi.search;
        let result = [];
        if (this.activepasien.rwi.search == 0) {
          return this.activepasien.rwi.data;
        }
        this.activepasien.rwi.data.forEach((element) => {
          // console.log(element.PatientName);
          if (
            element.PatientName.toString()
              .toLowerCase()
              .indexOf(st.toLowerCase()) > -1 ||
            element.NoMR.toString().toLowerCase().indexOf(st.toLowerCase()) > -1
          ) {
            result.push(element);
          }
        });
        return result;
      }
    },
    receipts_rj_store() {
      let temp= this.$store.getters.list_resep.list_resep;
      if(temp){
        this.receipts_rj=temp;
      }
      return temp;
    },
  },
  methods: {
    get_notification_list(){
axios.get("/api/notification_list").then((response) => {
        this.notification_list = response.data.data;
      });
    },
    activate(index) {
      this.active = index;
      
    },
    get_reciepts() {
      // add api call to get reseps instead of dispatch action
      // this.reseps=
if(this.active <= 1){
       
      this.$store.dispatch("get_allreceipts", {
        nama: this.nama_search,
        type: this.active == 0 ? "rj" : "ri",
      });
       
      }
      else if(this.active==3){
        axios.get("/api/notification_list").then((response) => {
        this.notification_list = response.data.data;
        this.receipts_rj=this.notification_list;
      });
      }
      else if(this.active==2){
         this.$store.dispatch("get_allreceipts", {
        nama: this.nama_search,
        type: "B",
      });
      }
    },
    randomstring() {
            var result = "";
            var characters =
                "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            var charactersLength = characters.length;
            for (var i = 0; i < 15; i++) {
                result += characters.charAt(
                    Math.floor(Math.random() * charactersLength)
                );
            }
            return result;
        },
    get_reciepts_pasien(reg) {
      this.internal_direct(
        "/farmasi/resep/" +
          this.randomstring() +
          "_" +
          reg.NoResep +
          "_" +
          this.randomstring()
      );
    },
    internal_direct(path) {
      this.$router.push(path);
    },
    sub(str) {
      return str.substr(-3);
    },
    get_list_rwj_rwi() {
      axios.post("/api/get_allvisit", {}).then((response) => {
        this.activepasien.rwj.data = response.data.list_visit;
      });
      axios.post("/api/get_allinap", {}).then((response) => {
        this.activepasien.rwi.data = response.data.list_inap;
        this.activepasien.show = true;
      });
    },
   
    text_to_date(text) {
      let date = text.substr(0, 10);
      return date;
    },
  },
};
</script>
<style scoped>

 .notification-number {
  
  z-index: 1;
  background: #cc2311;
  
  border-radius: 50%;
  padding: 5px;
  padding-top: 5px;

  
  text-align: center;
  font-size: 10px;
  font-weight: 700;
  line-height: 10px;
  color: #FFF;
  /* -webkit-animation: bounce 1s infinite; */


}

.dropdown {
  /* display: inline-block; */
  float: right;
  right: 0;
  z-index: 100;
  top: 0%;
  position: relative;
}

.dd-button {
  display: inline-block;
  border: 1px solid gray;
  color: #000000;
  border-radius: 4px;
  margin-top: 10px;
  padding: 10px 30px 10px 20px;
  background-color: #ffffff;
  cursor: pointer;
  white-space: nowrap;
}

.dd-button:after {
  content: "";
  position: absolute;
  top: 50%;
  right: 15px;
  transform: translateY(-50%);
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid black;
}

.dd-button:hover {
  background-color: #eeeeee;
}

.dd-input {
  display: none;
}

.dd-menu {
  position: absolute;
  top: 100%;
  border: 1px solid #ccc;
  border-radius: 4px;
  padding: 0;
  margin: 2px 0 0 0;
  box-shadow: 0 0 6px 0 rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
  list-style-type: none;
}

.dd-input + .dd-menu {
  display: none;
}

.dd-input:checked + .dd-menu {
  display: block;
}

.dd-menu li {
  padding: 10px 20px;
  cursor: pointer;
  color: #000000;
  white-space: nowrap;
}

.dd-menu li:hover {
  background-color: #f6f6f6;
}

.dd-menu li a {
  display: block;
  margin: -10px -20px;
  padding: 10px 20px;
}

.dd-menu li.divider {
  padding: 0;
  border-bottom: 1px solid #cccccc;
}
</style>
<style scoped>


</style>