<template>
  <div>
    <div v-if="show_cur_operationform" name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="chip right" @click="show_cur_operationform = false">X</div>

            <!-- <div class="modal-header">
              <slot name="header"> {{pasien_placeholder}}</slot>
            </div>-->

            <div class="modal-body">
              <div name="body">
                <input type="text" placeholder="Cari Pasien Nama / No MR" v-model="search" @keyup.enter="search_pasien()" />
                <ul v-if="search_data.length > 0" class="collection" style="max-height: 250px; overflow-y: auto">
                  <li v-for="(pasien, ind) in search_data" :key="ind" class="collection-item avatar" @click.prevent="select_pasien(pasien)">
                    <div>
                      <span class="title">{{ pasien.PatientName }}</span>
                      (
                      <span class="title">{{ pasien.NoMR }}</span
                      >)
                      <!-- (<span class="title">{{pasien.NoPesertaBPJS}}</span>) -->
                    </div>
                  </li>
                </ul>
                <input type="date" name v-model="cur_operation.tanggal" />
                <input type="text" placeholder="Jenis Tindakan Operasi" name v-model="cur_operation.jenis_tindakan" />
                <!-- <select class="browser-default" 
                    :v-model="cur_doc" aria-placeholder="Pilih Doctor"
                 >

                  <option
                    v-for="(dok,indi) in doctors"
                    :key="indi"
                    @change="cur_doc=dok"
                    :value="dok['First_Name']+' '+(dok['Last_Name']?dok['Last_Name']:'')+' '+dok['Spesialis']"
                  >
                    <span>{{dok['First_Name']+' '+(dok['Last_Name']?dok['Last_Name']:'')+' '+dok['Spesialis']}}</span>
                  </option>
                </select> -->
                <div class="input-field col s12">
                  <i class="prefix">dr.</i>
                  <input
                    id="dok"
                    name="dok"
                    type="text"
                    autocomplete="off"
                    placeholder="Cari Dokter"
                    v-model="cur_doc.First_Name"
                    @mousedown="
                      show_dok = true;
                      dok_search = '';
                    "
                  />
                </div>
                <ul v-if="doctors && show_dok" class="collection" style="max-height: 400px; overflow-y: auto">
                  <li v-for="(dok, ind) in doctors" :key="ind" class="collection-item avatar" @click.prevent="get_curdoc(dok)">
                    <div>
                      <img :src="'/images/avatar/' + dok.foto" alt class="circle" />
                      <span class="title">{{ dok.First_Name }} {{ dok.Last_Name }}</span>
                      <p>
                        Dokter {{ dok.Spesialis ? "Spesialis" : "Umum" }}
                        <br />
                      </p>

                      <a class="secondary-content">
                        <i class="material-icons">grade</i>
                      </a>
                    </div>
                  </li>
                </ul>
                Terlaksana :

                <i v-if="cur_operation.terlaksana == '0'" @click="cur_operation.terlaksana = '1'" class="fa fa-circle-notch"></i>
                <i v-else class="fa fa-check-circle" @click="cur_operation.terlaksana = '0'"></i>

                <br />
                <button class="btn waves-effect waves-light" type="submit" name="action" @click="save_pasien()">
                  Submit
                  <i class="material-icons right">send</i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <table class="table striped highlight container" style="left: 0; height: 600px; overflow-y: auto">
      <thead>
        <tr>
          <th>Tgl</th>
          <th>Nama</th>
          <th>No. RM</th>
          <th>Tindakan</th>
          <th>Unit</th>
          <th>Terlaksana</th>

          <th>
            <a class="btn-floating btn-large waves-effect waves-light red" @click="enable_form()">
              <i class="material-icons">add</i>
            </a>
          </th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(op, ind) in all_operations" :key="ind" @click="set_operation(op)">
          <td>{{ op.tanggal }}</td>
          <td>{{ op.nama }}</td>
          <td>{{ op.rm }}</td>
          <td>{{ op.jenis_tindakan }}</td>
          <td>{{ op.kode_poli }}</td>
          <td>
            <i v-if="op.terlaksana == '0'" class="fa fa-circle-notch"></i>
            <i v-else class="fa fa-check-circle"></i>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      show_dok: false,
      show_cur_operationform: false,
      search: "",
      cur_doc: {},
      search_data: [],
      cur_operation: {
        tanggal: "",
        jenis_tindakan: "",
        kode_poli: "",
        nama_poli: "",
        terlaksana: "0",
        nopeserta: "",
        rm: "",
        nama: "",
        operator: "",
        reg: "",
      },
      operations: [],
      search_operation: "",
      default_operation: {
        tanggal: "",
        jenis_tindakan: "",
        kode_poli: "",
        nama_poli: "",
        terlaksana: "0",
        nopeserta: "",
        rm: "",
        nama: "",
        reg: "",
      },
    };
  },
  computed: {
    doctors() {
      let list_doctors = this.$store.getters.get_mstr_doctors;
      let result = [];

      list_doctors.forEach((element) => {
        // console.log(element.PatientName);
        if (element.Spesialis) {
          result.push(element);
        }
      });
      return result;
    },
    all_operations() {
      let temp = [];
      if (this.operations.length > 0) {
        return this.operations;
      }
    },
  },
  mounted() {
    this.get_alloperations();
  },
  methods: {
    get_alloperations() {
      axios.post("/api/get_operation", {}).then((response) => {
        this.operations = response.data.operations;
      });
    },
    set_operation(op) {
      this.cur_operation = op;
      this.search = op.nama;
      this.cur_doc.First_Name = op.dpjp;
      this.cur_doc.codeBPJS = op.kode_poli;

      this.show_cur_operationform = true;
    },
    get_operations() {
      axios
        .post("/api/post_api", {
          url: "http://api.rstelagabunda.com:8081/api/get_operations",
          data: {},
          token: this.$store.getters.get_mstr_rstb_api_token,
        })
        .then((response) => {
          this.operations = response.data.data;
        })
        .catch((error) => {
          if (error.response) {
            console.log(error.response.data); //Logs a string: Error: Request failed with status code 404
          }
        });
    },
    get_curdoc(dok) {
      console.log("entered");
      this.show_dok = false;
      this.cur_doc = dok;
    },
    enable_form() {
      this.search = "";
      this.cur_operation = {
        tanggal: "",
        jenis_tindakan: "",
        kode_poli: "",

        terlaksana: "0",
        nopeserta: "",

        rm: "",
        nama: "",
        reg: "-",
      };
      this.show_cur_operationform = true;
      this.search_data = [];
    },
    search_pasien() {
      axios
        .post("/api/search_pasien", {
          search: this.search,
        })
        .then((response) => {
          this.search_data = response.data.data;
        })
        .catch((error) => {
          if (error.response) {
            console.log(error.response.data); //Logs a string: Error: Request failed with status code 404
          }
        });
    },
    select_pasien(pasien) {
      this.cur_operation.nopeserta = pasien.NoPesertaBPJS ? pasien.NoPesertaBPJS : "-";
      this.cur_operation.rm = pasien.NoMR;
      this.cur_operation.nama = pasien.PatientName;
      this.search = pasien.PatientName;
      // this.cur_operation.poli=
      this.search_data = [];
      //   this.show_cur_operationform = false;
    },
    save_pasien() {
      this.cur_operation.kode_poli = this.cur_doc.codeBPJS;
      this.cur_operation.dpjp = this.cur_doc.First_Name;

      axios
        .post("/api/save_operation", this.cur_operation)
        .then((response) => {
          let operation = response.data.operation;
          axios
            .post("/api/post_api", {
              url: "http://api.rstelagabunda.com:8081/api/save_operation",
              data: operation,
              token: this.$store.getters.get_mstr_rstb_api_token,
            })
            .then((response) => {
              this.show_cur_operationform = false;
              this.get_alloperations();
            })
            .catch((error) => {
              if (error.response) {
                console.log(error.response.data); //Logs a string: Error: Request failed with status code 404
              }
            });
        })
        .catch((error) => {
          if (error.response) {
            console.log(error.response.data); //Logs a string: Error: Request failed with status code 404
          }
        });
      // axios
      //     .post("/api/post_api", {
      //         url: "https://api.rstelagabunda.com/api/save_operation",
      //         data: this.cur_operation,
      //         token: this.$store.getters.get_mstr_rstb_api_token,
      //     })
      //     .then((response) => {
      //         this.search_data = response.data.data;
      //     })
      //     .catch((error) => {
      //         if (error.response) {
      //             console.log(error.response.data); //Logs a string: Error: Request failed with status code 404
      //         }
      //     });
    },
  },
};
</script>

<style></style>
