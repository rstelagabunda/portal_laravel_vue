<template>
  <div class="main">
    <div class="center">
      <p class="btn-floating pulse">
        <i class="material-icons">local_hospital</i>
      </p>
      <div class="chip h1 white-text teal">{{welcome}}</div>
    </div>

    <div class="container">
      <div class="col s12 m8 offset-m2 l6 offset-l3">
        <div
          class="card-panel grey lighten-5 z-depth-1"
          @click="external_direct('//172.100.15.179/sismadak')"
        >
          <div class="row valign-wrapper">
            <div class="col s2">
              <img src="images/common/sismadak_v2.png" alt class="circle responsive-img" />
              <!-- notice the "circle" class -->
            </div>
            <div class="col s10">
              <a class="subheader strong bolder">S I S M A D A K</a>
              <br />
              <span class="black-text">Sistem Manajemen Dokumen Akreditasi merupakan aplikasi.</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col s12 m8 offset-m2 l6 offset-l3">
        <div
          class="card-panel grey lighten-5 z-depth-1"
          target="_blank"
          @click="internal_direct('/ppi')"
        >
          <div class="row valign-wrapper">
            <div class="col s2">
              <img src="images/common/ppi.png" alt class="circle responsive-img" />
              <!-- notice the "circle" class -->
            </div>
            <div class="col s10">
              <a class="subheader strong bolder">P P I</a>
              <br />
              <span class="black-text">pencegahan dan pengendalian infeksi ( PPI)</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col s12 m8 offset-m2 l6 offset-l3">
        <div class="card-panel grey lighten-5 z-depth-1" @click="internal_direct('/library')">
          <div class="row valign-wrapper">
            <div class="col s2">
              <img src="images/common/library.png" alt class="circle responsive-img" />
              <!-- notice the "circle" class -->
            </div>
            <div class="col s10">
              <a class="subheader strong bolder">perpustakaan</a>
              <br />
              <span class="black-text">sebuah koleksi PDF / E-Book</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col s12 m8 offset-m2 l6 offset-l3">
        <div class="card-panel grey lighten-5 z-depth-1" @click="internal_direct('/laporan')">
          <div class="row valign-wrapper">
            <div class="col s2">
              <img src="images/common/laporan.png" alt class="circle responsive-img" />
              <!-- notice the "circle" class -->
            </div>
            <div class="col s10">
              <a class="subheader strong bolder">Laporan</a>
              <br />
              <span class="black-text">Laporan</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  methods: {
    internal_direct(path) {
      this.$router.push(path);
    },
    external_direct(path) {
      window.open(path, "_blank");
    }
  },

  computed: {
    welcome() {
      return this.$store.getters.welcomemessage;
    }
  }
};
</script>