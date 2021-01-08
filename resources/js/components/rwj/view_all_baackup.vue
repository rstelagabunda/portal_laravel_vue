<template>
  <div class="container">
    <div class="row">
      <!-- <i class="material-icons">{{categories[active].icon}}</i> -->
      <!-- <select class="browser-default" @change="get_cur_doc($event.target.value)">
    <option value=""  selected>-- Pilih Doctor --</option>
    <option :value="doc.ID" v-for="(doc,ind) in list_doctors" :key="ind" >
        <div class="chip">
            
            {{doc.First_Name}}   
  </div>

    </option>
   
      </select>-->

      <input
        type="text"
        autocomplete="off"
        placeholder="Cari Dokter"
        v-model="dok_search"
        @focus="show_dok=true;dok_search=''"
      />
      <!-- <ul class="list-group" ng-hide="hidethis" style="height:auto;max-height: 200px; overflow: auto;color:black" >
    <li class="list-group-item" ng-repeat="diag in list_diag" ng-click="filltextbox(diag)" >{{diag.nama}}</li>
      </ul>-->
      <!-- <span v-if="list_doctors_cur&&show_dok" class="chip left" style="position: -webkit-sticky;
  position: sticky;
      top: 0;" @click="show_dok=false">X</span>-->
      <div v-if="list_doctors_cur&&show_dok">
        <span class="chip" @click="get_cur_doc('');dok_search='';show_dok=false">-- Semua --</span>

        <span class="chip right" @click="show_dok=false">X</span>
      </div>
      <ul
        v-if="list_doctors_cur&&show_dok"
        class="collection"
        style="max-height:250px; overflow-y: auto;"
      >
       
        <li
          v-for="(dok,ind) in list_doctors_cur"
          :key="ind"
          class="collection-item avatar"
          @click.prevent="get_cur_doc(dok.ID);dok_search=dok.First_Name;show_dok=false"
        >
          <div>
            <img :src="'images/avatar/'+dok.foto" alt class="circle" />
            <span class="title">{{dok.First_Name}} {{dok.Last_Name}}</span>
            <p>
              Dokter {{dok.Spesialis?'Spesialis':'Umum'}}
              <br />
            </p>

            <a class="secondary-content">
              <i class="material-icons">grade</i>
            </a>
          </div>
        </li>
      </ul>
      <div class="input-field col s12" style="margin-bottom:1px">
        <input
          autocomplete="off"
          v-model="nama_search"
          v-on:keyup="get_allvisit(nama_search)"
          id="first_name"
          type="text"
          class="validate"
        />
        <label for="first_name">Cari Pasien / no MR</label>
      </div>
    </div>
    <table class="table striped highlight" style="left:0">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Tgl</th>

          <th>Dokter</th>

          <th>NoMR</th>
          <th @click="sort_antri()">
            Antrian
            <i class="material-icons">sort</i>
          </th>
        </tr>
      </thead>

      <tbody v-if="list_allvisit_d">
        <tr
          :class="visit.Selesai==1?'selsai':''"
          v-for="visit in list_allvisit_d"
          :key="visit.sdfsdf"
        >
          <td @click="internal_direct(visit.NoRegistrasi)">{{visit.PatientName | capitalize}}</td>

          <td>
            {{visit.TglKunjungan | formatDate}}
            <span class="badge white">{{visit['Status Name']}}</span>
          </td>

          <td>
            {{visit['First_Name']}} {{visit['Last_Name']}} {{visit['Spesialis']}}
            <span
              class="badge white"
            >{{visit['codeBPJS']}}</span>
          </td>
          <td>{{visit.NoMR}}</td>
          <td >
            <span class="badge white">{{visit['Antrian']}}</span>
            <!-- <i v-if="visit.Selesai==1" class="material-icons">check_box</i>
            <i v-else class="material-icons">check_box_outline_blank</i> -->
          </td>
        </tr>
      </tbody>
      <tr v-if="!list_allvisit_d">
        <td></td>
        <td colspan="4">-- tidak ada Pasien --</td>
        <td></td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  mounted() {
    this.$store.dispatch("get_allvisit", {
      search: this.nama_search,
      dok: this.cur_doc_id
    });
    // this.$store.dispatch("get_mstr_doctors");
     },
  data() {
    return {
      nama_search: "",
      show_dok: false,
      dok_search: "",
      show_modal: true,
      cur_doc_id: null,
      //list_allvisit_d:null,
      asc: 1
    };
  },
  computed: {
    list_allvisit_a() {
      //this.list_allvisit_d=this.$store.getters.get_allvisit;
      return this.$store.getters.get_allvisit;
      //this.list_allvisit_d=[];
    },
    list_allvisit_d() {
      if (this.list_allvisit_a.length > 0) {
        let st = this.nama_search;
        let result = [];
        if (this.nama_search.length == 0) {
          return this.list_allvisit_a;
        }
        this.list_allvisit_a.forEach(element => {
          //console.log(element.PatientName);
          if (
            element.PatientName.toString()
              .toLowerCase()
              .indexOf(st) > -1 ||
            element.NoMR.toString()
              .toLowerCase()
              .indexOf(st) > -1 ||
            element.ID.toString()
              .toLowerCase()
              .indexOf(st) > -1
          ) {
            result.push(element);
          }
        });
        return result;
      }
    },

    list_doctors() {
      return this.$store.getters.get_mstr_doctors;
    },
    list_doctors_cur() {
      if (this.list_doctors.length > 0) {
        let st = this.dok_search;
        let result = [];
        if (this.dok_search.length == 0) {
          return this.list_doctors;
        }
        this.list_doctors.forEach(element => {
          // console.log(element.PatientName);
          if (
            element.First_Name.toString()
              .toLowerCase()
              .indexOf(st.toLowerCase()) > -1
          ) {
            result.push(element);
          }
        });
        return result;
      }
    }
  },
  methods: {
    sort_antri() {
      this.asc = this.asc * -1;
      this.list_allvisit_d.sort((t1, t2) =>
        parseInt(t1.Antrian) < parseInt(t2.Antrian) ? this.asc : -this.asc
      );
    },
    get_cur_doc(id) {
      console.log(id);
      this.cur_doc_id = id;
      this.get_allvisit("dok");
    },
    get_allvisit(st) {
      if (st == "dok") {
        this.nama_search = "";
        this.$store.dispatch("get_allvisit", {
          search: this.nama_search,
          dok: this.cur_doc_id
        });
      } else {
        let result = [];

        //
      }
    },
    internal_direct(path) {
      // this.$store.dispatch('get_pasiendata_noreg',{"noreg":path});
      this.$router.push("/rwj/emr/" + path);
    },
    sub(str) {
      return str.substr(-3);
    },
    selesai(id) {
      axios.post("/api/visit_selesai", id).then(response => {
        M.toast({ html: "Berhasil  !" });
      });
    }
  }
};
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
  background: radial-gradient(
    circle,
    rgba(238, 174, 202, 1) 0%,
    rgba(148, 187, 233, 1) 100%
  );
}
.tabs {
  position: relative;
  width: 100%;
  height: 100%;
  background-color: #fff;
}
.tabs-triggers {
  display: flex;
}
.tabs-trigger {
  flex: 1 0 auto;
  margin: 0;
  padding: 1rem;
  background-color: #fff;
  font-weight: bold;
  transition: 100ms linear all;
  cursor: pointer;
}
.tabs-trigger--active {
  background-color: rgb(43, 98, 129);
  color: white;
  border: 1px solid white;
  border-radius: 10px;
}
.tabs-content {
  padding: 1rem;
  background-color: #fff;
}
</style>