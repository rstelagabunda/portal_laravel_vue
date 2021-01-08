<template>
  <div v-if="roles&&token">

      <div name="body">
                <ul class="collection" style="max-height:100vh;width:50%;float:left;overflow-y:auto">
                  <li
                    v-for="(dok,ind) in list_doctors_cur_s"
                    :key="ind"
                    class="collection-item avatar"
                    :style="cur_jadwal.kode.toUpperCase()==dok.code_mjkn.toUpperCase()?'border:5px solid pink;border-radius:10px;color:green':''"
                    @click.prevent="get_cur_jadwal(dok.code_mjkn,dok.First_Name+' '+dok.Last_Name+' '+dok.Spesialis)"
                  >
                    <div v-if="dok.Spesialis"  >
                      <img :src="'/images/avatar/'+dok.foto" alt class="circle" />
                      <span class="title" >{{dok.First_Name}}</span>
                      <p>
                        Dokter {{dok.Spesialis?'Spesialis':'Umum'}}
                        <br />
                      </p>

                     
                    </div>
                  </li>
                </ul>
                 <span style="font-style:italic;text-align:center;font-size:18px">
                        Doktor : {{cur_jadwal.dok}}
                    </span>
                <ul style="position:relative;width:46%;margin-left:1%;float:left;max-height:80vh;margin-top:20px;overflow-y:auto">
                   
                    <li>
                        <div class="col s12" v-for="(i,ind) in [0,1,2,3,4,5,6]" :key="ind">
    <h2 style="font-style:italic;text-align:center;font-size:15px">{{cur_jadwal[i]["day"]}}</h2>
    <div class="card horizontal">
      
      <div class="card-stacked">

        <div class="card-content" >
           Mulai : <input type="time" name="minggu" id="minggu" v-model="cur_jadwal[i]['mulai']"> Tutup : <input type="time" name="minggu" id="minggu" v-model="cur_jadwal[i]['tutup']">
           <br>
           <button v-if="cur_jadwal.dok!=''" @click="save_jadwal(cur_jadwal[i]['id'],i)" class="btn">Simpan</button>
        </div>
        
      </div>
    </div>
  </div>
                      
                    </li>
                    <!-- <li>
                        Senin : <input type="time" name="minggu" id="minggu" v-model="cur_jadwal['1']">
                    </li>
                    <li>
                        Selasa : <input type="time" name="minggu" id="minggu" v-model="cur_jadwal['2']">
                    </li>
                    <li>
                        Rabu : <input type="time" name="minggu" id="minggu" v-model="cur_jadwal['3']">
                    </li>
                    <li>
                        Kamis : <input type="time" name="minggu" id="minggu" v-model="cur_jadwal['4']">
                    </li>
                    <li>
                        Jumat : <input type="time" name="minggu" id="minggu" v-model="cur_jadwal['5']">
                    </li>
                    <li>
                        Sabtu : <input type="time" name="minggu" id="minggu" v-model="cur_jadwal['6']">
                    </li> -->
                    
                </ul>
              </div>
  </div>
</template>


<script>
import Axios from 'axios';
export default {
  data() {
    return {
      doctors: null,
      cur_jadwal:{
          0:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Minggu"},
          1:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Senin"},
          2:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Selasa"},
          3:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Rabu"},
          4:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Kamis"},
          5:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Jumat"},
          6:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Sabtu"},
          "dok":'',
          "poli":'',
          "kode":''
          
      }
    //   token: ""
    };
  },
  mounted() {
    // this.$store.dispatch("master");
    // this.$store.dispatch("get_apirstb_token");
  },
  computed: {
      list_doctors_cur_s(){
          let doctors=this.$store.getters.get_mstr_doctors;
          let doc_f=[];
          doctors.forEach(element => {
              if(element.Spesialis){
              doc_f.push(element)
              }
          });
      return doc_f;
      },
    token() {
        if(this.$store.getters.get_mstr_rstb_api_token!=''){
            axios.post('/api/get_api_doctors',{url:"https://api.rstelagabunda.com/api/get_doctors",token:this.$store.getters.get_mstr_rstb_api_token})
            .then(response => {
                // console.log(response.data);
                this.doctors=response.data.doctors;
            })
        .catch(error => {
          if (error.response) {
            console.log(error.response.data); //Logs a string: Error: Request failed with status code 404
          }
        });
        }
        return this.$store.getters.get_mstr_rstb_api_token;
     
      
    },
    roles() {
      let roles = this.$store.getters.get_roles;
      console.log("roles are " + roles);

      if (roles.rwj != undefined) {
        if (roles.rwj != true || (roles.rwj == true && roles.head == false)) {
          // console.log(roles);
          this.$destroy();
          this.$router.push("/rwj/emr");
          this.$toastr.e(
            "Anda Gak Punya Akses ke 'Jadwal Doktor'",
            "Gak Ada Akses!"
          );
        }
      }

      return roles ? roles : {};
    }
  },
  methods: {
    get_doctors() {
         axios.post('/api/get_api_doctors',{url:"https://api.rstelagabunda.com/api/get_doctors",token:this.$store.getters.get_mstr_rstb_api_token})
            .then(response => {
                // console.log(response.data);
                this.doctors=response.data.doctors;
                if(this.cur_jadwal.kode!=''){
                    this.get_cur_jadwal(this.cur_jadwal.kode);
                }
            })
        .catch(error => {
          if (error.response) {
            // console.log(error.response.data); //Logs a string: Error: Request failed with status code 404
          }
        });
    },
    get_cur_jadwal(kode,name){
        this.cur_jadwal={
           0:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Minggu"},
          1:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Senin"},
          2:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Selasa"},
          3:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Rabu"},
          4:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Kamis"},
          5:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Jumat"},
          6:{"mulai":"00:00","tutup":"00:00","id":null,"day":"Sabtu"},
          "dok":'',
          "poli":'',
          "kode":kode.toUpperCase()
          
      }
        this.doctors.forEach(element => {
            if(kode.toUpperCase()==element.kode.toUpperCase()){
                this.cur_jadwal.dok==''?this.cur_jadwal.dok=element.nama:'';
                this.cur_jadwal.poli==''?this.cur_jadwal.poli=element.kode_poli:'';
                // this.cur_jadwal.kode==''?this.cur_jadwal.kode=element.kode:'';


                this.cur_jadwal[element.kode_day]["mulai"]=element.mulai;
                this.cur_jadwal[element.kode_day]["tutup"]=element.tutup;
                this.cur_jadwal[element.kode_day]["id"]=element.id;

            }
        });
        this.cur_jadwal.dok==''?this.cur_jadwal.dok=name:'';
    },
    save_jadwal(id,day){
        //console.log(id);
        let url="https://api.rstelagabunda.com/api/insert_doctor";
let jad={
    id:id,
kode: this.cur_jadwal.kode,
kode_day: day,
kode_poli: this.cur_jadwal.poli,
mulai: (this.cur_jadwal[day]["mulai"]+":00").substring(0,8),
nama: this.cur_jadwal.dok,
tutup:(this.cur_jadwal[day]["tutup"]+":00").substring(0,8)
}
if(id!=null){
    jad["id"]=id;
url="https://api.rstelagabunda.com/api/update_doctor";
}
 axios.post('/api/get_api_doctors',{url:url,jad:jad,token:this.$store.getters.get_mstr_rstb_api_token})
            .then(response => {
                // console.log(response.data);
                this.$toastr.s("Sudah Simpan", "Sukses!");
                this.get_doctors();
            })
        .catch(error => {
          if (error.response) {
            console.log(error.response.data); //Logs a string: Error: Request failed with status code 404
          }
        });

    }
  }
};
</script>