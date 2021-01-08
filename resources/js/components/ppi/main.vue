<template>
  <div class="container">
    <line-chart :data="ppi_data"></line-chart>
    <!-- <div class="container">
        <table>
            <tr>
                <td> <label for="">Start</label> </td>
                <td> <input :v-model="start_date"  id="start_date" @change="getdate('start')" type="date" date name="start" > </td>
                <td class="badge"> <label for="">End</label> </td>
                <td> <input type="date" id="end_date" name="end"   @change="getdate('end')" > </td>
                <td><a class="btn-floating btn waves-effect waves-light teal" @click="get_data()" ><i class="material-icons">compare_arrows</i></a></td>

            </tr>
        </table>
     
    </div>-->
    <button @click="get_data()" class="btn">data IDO</button>
    <table v-if="ido_data" class="striped" style="height:400px;y-scroll:true">
      <thead>
        <tr>
          <th>Nama_Pasien</th>

          <th>Tgl_Masuk</th>
          <th>Kode_Diagnosa</th>
          <th>Descripsi_diagnosa</th>
        </tr>
      </thead>
      <tbody
        style=" height: 100px; width:300;     
    overflow-y: auto;    
    overflow-x: hidden;"
      >
        <tr v-for="(item,ind) in ido_data" :key="ind">
          <td>{{sub_ss(item.PatientName)}}</td>
          <td>{{item.StartDate | formatDate}}</td>
          <td>{{item.code_list}}</td>
          <td>{{item.desc_list}}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  mounted() {
    this.$store.dispatch("get_allreports_ppi");
  },
  computed: {
    ppi_data() {
      return this.$store.getters.get_reports_ppi;
    }
  },
  data() {
    return {
      start_date: "",
      end_date: "",
      ido_data: []
    };
  },
  methods: {
    getdate(str) {
      this.start_date = document.getElementById("start_date").value;

      this.end_date = document.getElementById("end_date").value;
    },
    get_data() {
      axios
        .post(
          "/api/get_ido",
          { start_date: this.start_date, end_date: this.end_date },
          {}
        )
        .then(response => {
          this.ido_data = response.data.ido;

          M.toast({ html: "Berhasil  !" });
        });
    },
    sub_ss(st) {
      var sub = st.split(" ");
      var final = "";
      sub.forEach(element => {
        final = final + element[0] + "*** ";
      });
      return final;
    }
  }
};
</script>

<style>
</style>