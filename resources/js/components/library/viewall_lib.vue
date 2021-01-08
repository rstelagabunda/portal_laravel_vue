
<template>
  <div class="container">
    <div class="row">
      <!-- <button id="show-modal" @click="showModal = true">Show Modal</button> -->
      <!-- <modal
      v-show="showModal"
      @close="showModal=false">

      <h3 slot="header">custom header</h3>
      </modal>-->
      <div class="input-field col s12" style="margin-bottom:1px">
        <input
          v-model="search_name"
          v-on:keyup.enter="call_lib()"
          id="first_name"
          type="text"
          class="validate"
        />
        <label for="first_name">Cari Dokumen</label>
      </div>
    </div>

    <table>
      <tr v-for="(column,index) in list_lib" :key="index">
        <td v-for="(item,ex_index) in column" :key="ex_index">
          <!-- <div class="row">
    <div class="col s12 m7">
      <div class="card">
        <div class="card-image">
          <img  src="images/pdf.png">
         <span class="h4 float-text"> {{item.name}}</span>
        </div> 
        <span class="flow-text"> {{item.name }}</span>
        
        
      </div>
    </div>
          </div>-->

          <div class="col s12">
            <h6>
              <p
                class="z-depth-4"
                style="border:1px solid gray;padding:15px;width:80/cols;border-radius:10px"
              >{{item.name | capitalize}}</p>
            </h6>
          </div>

          <div class="card-content">
            <a
              class="waves-effect waves-light btn modal-trigger"
              data-toggle="modal"
              data-modal="modal1"
              @click="get_file(item.name)"
              data-target="modal1"
            >Buka</a>
          </div>
        </td>
      </tr>
      <tr>
        <!-- <td v-for="(a,ai) in cols-2" :key="ai"></td> -->
        <td :colspan="cols-1">
          <ul class="pagination">
            <li :class="full_meta.current_page==1?'disabled':'waves-effect'">
              <a @click="ajax_call(full_meta.path+'?page='+(full_meta.current_page-1))">
                <i class="material-icons">chevron_left</i>
              </a>
            </li>
            <li
              v-for="(l,i) in full_meta.last_page"
              :key="i"
              :class="l==full_meta.current_page?'active':'waves-effect'"
            >
              <a @click="ajax_call(full_meta.path+'?page='+l)">{{l}}</a>
            </li>
            <!-- <li class="waves-effect"><a href="#!">2</a></li>
    <li class="waves-effect"><a href="#!">3</a></li>
    <li class="waves-effect"><a href="#!">4</a></li>
            <li class="waves-effect"><a href="#!">5</a></li>-->
            <li :class="full_meta.current_page==full_meta.last_page?'disabled':'waves-effect'">
              <a @click="ajax_call(full_meta.next_page_url)">
                <i class="material-icons">chevron_right</i>
              </a>
            </li>
            <li>
              Tampilkan
              <span class="teal-text">{{full_meta.from}}</span> ke
              <span class="teal-text">{{full_meta.to}}</span> dari
              <span class="pink-text">{{full_meta.total}}</span>
            </li>
          </ul>
        </td>
        <td></td>
      </tr>
    </table>
    <!-- <div class="col1" >
  <div  class="item-container1"  >


 
         

  
    </div>

    </div>-->

    <!-- <a class="waves-effect waves-light btn modal-trigger" @click="get_file('0012r0060918v000010')" href="#modal1">Modal</a>
<a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal</a>
    <a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal</a>-->
    <!-- Modal Structure -->
    <div id="modal1" class="modal" style="width:98%;height:100%">
      <a class="modal-close waves-effect waves-green btn-flat">X</a>

      <iframe :src="file_load" style="width:100%;height:90%" type="application/pdf"></iframe>
    </div>
  </div>
</template>
<style>
.container1 {
  display: flex;
}

.col1 {
  margin: 10px;

  flex-grow: 1;
  display: flex;
  flex-direction: column;
}
.item-container1 {
  border: 1px solid transparent;
  border-radius: 10px;
  padding: 5px;
  margin: 5px;
}
</style>
<script>
import modal from "./modal.vue";
export default {
  data() {
    return {
      file_load: "",
      showModal: true,
      cols: 2,
      rows: 0,
      window: {
        width: 0,
        height: 0
      },
      items: [],
      search_name: "",
      full_meta: []
    };
  },
  filters: {
    subStr: function(string) {
      return string.length > 20 ? string.substring(0, 30) + ".." : string;
    }
  },
  mounted() {
    this.$store.dispatch("getListLib", { name: this.search_name });
  },
  created() {
    // document.getElementsByClassName('modal');
    window.addEventListener("resize", this.handleResize);
    this.handleResize();
  },
  destroyed() {
    window.removeEventListener("resize", this.handleResize);
  },
  computed: {
    search() {},

    list_lib() {
      if (this.$store.getters.list_lib.list_lib != undefined) {
        // console.log(this.$store.getters.list_lib.list_lib.data);
        this.full_meta = this.$store.getters.list_lib.list_lib;
        this.items = this.$store.getters.list_lib.list_lib.data;
      }
      let columns = [];
      let mid = Math.ceil(this.items.length / this.cols);
      this.rows = mid;
      for (var i = 0; i < this.items.length; i += this.cols) {
        columns.push(this.items.slice(i, i + this.cols));
      }
      return columns;
    }
  },
  methods: {
    call_lib() {
      this.$store.dispatch("getListLib", { name: this.search_name });
    },
    ajax_call(url) {
      axios.post(url, { name: this.search_name }, {}).then(response => {
        // this.full_meta=response.data.list_lib;
        // this.items=response.data.list_lib.data;
        this.$store.commit("updateListLib", response.data);
      });
    },
    handleResize() {
      this.window.width = window.innerWidth;
      this.window.height = window.innerHeight;
      if (this.window.width > 0 && this.window.width <= 479) {
        this.cols = 1;
      } else if (this.window.width > 479 && this.window.width <= 907) {
        this.cols = 2;
      } else if (this.window.width > 907 && this.window.width <= 1367) {
        this.cols = 3;
      } else {
        this.cols = 4;
      }
    },
    get_file(name) {
      this.file_load = "storage/library/" + name;
      // this.file_load="file://172.100.15.251:8080/winshare1/Library/0012R0060918V000001.pdf";
    },
    getfileload() {
      return this.file_load;
    }
  },
  components: {
    modal
  }
};
</script>

