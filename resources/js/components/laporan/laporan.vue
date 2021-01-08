<template>
  <div class="row">
    <div class="container">
      <table>
        <tr>
          <td>
            <label for>Start</label>
          </td>
          <td>
            <input
              :v-model="start_date"
              id="start_date"
              @change="getdate('start')"
              type="date"
              date
              name="start"
            />
          </td>
          <td class="badge">
            <label for>End</label>
          </td>
          <td>
            <input type="date" id="end_date" name="end" @change="getdate('end')" />
          </td>
          <td>
            <a class="btn-floating btn waves-effect waves-light teal" @click="get_laporan()">
              <i class="material-icons">compare_arrows</i>
            </a>
          </td>
        </tr>
      </table>
    </div>

    <div class="tabs" id="tabs">
      <div class="tabs-triggers">
        <div
          class="tabs-trigger"
          v-for="(item, index) in categories"
          :class="[index === active ? 'tabs-trigger--active' : '']"
          :key="index"
          @click="activate(item.ind)"
        >
          <i class="material-icons">{{item.icon}}</i>
          {{item.name}}
        </div>
      </div>
    </div>

    <div class="container">
      <div class="tabs-content" v-if="active === 0">
        <table v-if="categories[0].list[0]" class="striped">
          <thead>
            <tr>
              <th>Nama Dokter</th>
              <th>Jumlah( {{jumlah_rj}} )</th>
            </tr>
          </thead>
          <tr v-for="(item,ind) in categories[0].list" :key="ind">
            <td>
              dr {{item.First_Name}},
              <span class="badge">{{item.Spesialis}}</span>
            </td>
            <td>{{item.CountOfID}}</td>
          </tr>
        </table>
      </div>
      <div class="tabs-content" v-if="active === 1">
        <table v-if="categories[1].list[0]" class="striped">
          <thead>
            <tr>
              <th>Agama</th>
              <th>Pendidikan</th>

              <th>Jumlah( {{jumlah_rj_e}} )</th>
            </tr>
          </thead>
          <tr v-for="(item,ind) in categories[1].list" :key="ind">
            <td>{{(item.Religion=="" || item.Religion==null)?'Islam':item.Religion}}</td>
            <td>{{(item.Education=="" || item.Education==null)?'Belum Sekolah':item.Education}}</td>

            <td>{{item.CountOfID}}</td>
          </tr>
        </table>
      </div>

      <div class="tabs-content" v-if="active === 2">
        <table v-if="categories[2].list[0]" class="striped">
          <thead>
            <tr>
              <th>Nama Dokter</th>
              <th>Jumlah( {{jumlah_ri}} )</th>
            </tr>
          </thead>
          <tr v-for="(item,ind) in categories[2].list" :key="ind">
            <td>
              dr {{item.First_Name}},
              <span class="badge">{{item.Spesialis}}</span>
            </td>
            <td>{{item.CountOfID}}</td>
          </tr>
        </table>
      </div>

      <div class="tabs-content" v-if="active === 3">
        <table v-if="categories[3].list[0]" class="striped">
          <thead>
            <tr>
              <th>Agama</th>
              <th>Pendidikan</th>

              <th>Jumlah ( {{jumlah_ri_e}} )</th>
            </tr>
          </thead>
          <tr v-for="(item,ind) in categories[3].list" :key="ind">
            <td>{{(item.Religion=="" || item.Religion==null)?'Islam':item.Religion}}</td>
            <td>{{(item.Education=="" || item.Education==null)?'Belum Sekolah':item.Education}}</td>

            <td>{{item.CountOfID}}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      active: 0,
      start_date: "",
      end_date: "",
      categories: [
        {
          ind: 0,
          name: "Rawat Jalan per Dokter",
          icon: "directions_walk",
          list: []
        },
        {
          ind: 1,
          name: "Rawat Jalan per agama & pendidikan ",
          icon: "directions_walk",
          list: []
        },

        {
          ind: 2,
          name: "Rawat Inap",
          icon: "airline_seat_individual_suite",
          list: []
        },
        {
          ind: 3,
          name: "Rawat Inap per agama & pendidikan",
          icon: "airline_seat_individual_suite",
          list: []
        }
      ],
      jumlah_rj:0,
      jumlah_rj_e:0,
      jumlah_ri:0,
      jumlah_ri_e:0,

    };
  },
  methods: {
    activate(index) {
      this.active = index;
    },
    getdate(str) {
      this.start_date = document.getElementById("start_date").value;

      this.end_date = document.getElementById("end_date").value;
    },
    get_laporan() {
      axios
        .post(
          "/api/get_laporan",
          { start_date: this.start_date, end_date: this.end_date },
          {}
        )
        .then(response => {
          this.jumlah_rj=0,
      this.jumlah_rj_e=0,
      this.jumlah_ri=0,
      this.jumlah_ri_e=0,
          this.categories[0].list = response.data.rj;
          this.categories[1].list = response.data.rj_e;
          this.categories[2].list = response.data.ri;
          this.categories[3].list = response.data.ri_e;
          response.data.rj.forEach(element => {
            this.jumlah_rj=parseInt(this.jumlah_rj)+parseInt(element.CountOfID);
          });
          response.data.rj_e.forEach(element => {
            this.jumlah_rj_e=parseInt(this.jumlah_rj_e)+parseInt(element.CountOfID);
          });
          response.data.ri.forEach(element => {
            this.jumlah_ri=parseInt(this.jumlah_ri)+parseInt(element.CountOfID);
          });
          response.data.ri_e.forEach(element => {
            this.jumlah_ri_e=parseInt(this.jumlah_ri_e)+parseInt(element.CountOfID);
          });
          M.toast({ html: "Berhasil  !" });
        });
    }
  }
};
</script>

<style>
</style>