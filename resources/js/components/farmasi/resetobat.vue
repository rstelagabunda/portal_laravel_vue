<template>
  <div class="container">
    <div class="col s12">
      <div class="row">
        <!-- <i class="material-icons">{{categories[active].icon}}</i> -->

        <div class="input-field col s12" style="margin-bottom:1px">
          <input
            autocomplete="off"
            v-model="nama_search"
            v-on:keyup.enter="get_obat()"
            id="first_name"
            type="text"
            class="validate"
          />
          <label for="first_name">Cari Obat</label>
        </div>
      </div>
      <table class="striped highlight" style="left:0">
        <thead>
          <tr>
            <th>Nama obat</th>
            <th>stock_Farmasi</th>
            <th>stock_kembali</th>
            <th>stock_Terjual</th>
            <th>stock tertahan</th>
            <th>stock tersedia</th>

            <th>reset_Mutasi</th>
          </tr>
        </thead>

        <tbody v-if="list_obat">
          <tr v-for="(obt,inde) in list_obat" :key="inde">
            <td>{{obt["Product Name"] | capitalize}}</td>
            <td>{{obt["Qty Purchased"]}}</td>

            <td>{{obt["QtyReturCstr"]}}</td>

            <td>{{obt["Qty Sold"]}}</td>

            <td>{{obt["Qty On Hold"]}}</td>
            <td>{{ obt["Qty Purchased"]-obt["QtyReturCstr"]-obt["Qty On Hold"]-obt["Qty Sold"]}}</td>

            <td>
              <input type="number" v-model="obt['qty_reset_m']" />
            </td>
            <td>
              <button
                class="btn"
                @click="reset_obat(obt['Product ID'],0,obt['qty_reset_m'],obt['Standard Cost'])"
              >Reset</button>
            </td>
          </tr>
        </tbody>
        <tr v-if="!list_obat">
          <td></td>
          <td colspan="4">-- tidak ada obat --</td>
          <td></td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    this.$store.dispatch("get_stock_obat", { product: "" });
  },
  data() {
    return {
      nama_search: ""
    };
  },

  computed: {
    list_obat() {
      return this.$store.getters.get_stock_obat;
    }
  },
  methods: {
    get_obat() {
      this.$store.dispatch("get_stock_obat", { product: this.nama_search });
    },
    sum_all(p, r, h, s) {
      return parseInt(s);
      return parseInt(p)
        ? parseInt(p)
        : 0 + parseInt(r, 10)
        ? parseInt(r, 10)
        : 0; //-parseInt(h)-parseInt(s);
    },
    reset_obat(id, qty_s, qty_m, unit) {
      axios
        .post("/api/resetstock_instant", { id, qty_s, qty_m, unit }, {})
        .then(response => {
          this.get_obat();
          M.toast({ html: "Berhasil  !" });
        });
    }
  }
};
</script>

<style>
</style>