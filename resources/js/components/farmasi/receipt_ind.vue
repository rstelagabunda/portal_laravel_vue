<template>
  <div>
    <div name="resept" v-if="show.resep">
      <!-- <a
        @click="show.resep=false;show.list_resep=true"
        class="btn-floating btn-small pulse green"
        style="position:fixed;float:right"
      >
        <i class="material-icons">close</i>
      </a>-->
      <br />
      <!-- <div class="chip right" >X</div> -->
      <div>
        <!-- <table border="1" align="center" style="text-align:center">
                <thead >
                  <th style="text-align:center;align:center">Detail Pasien</th>
                  <th v-if="active==1" style="text-align:center;align:center">Resep</th>
                </thead>
                <tr>
        <td :style="active==1?'border-right:2px dashed red':''">-->
        <ul class="animate__animated animate__fadeInDownBig animate__slow">
          <li
            class="animate__animated animate__fadeInUp avatar col row s12 list header"
            style="padding: 1px"
          >
            <div class="badge col row s12 m6 center-align">
              <!-- <span class="title">{{current_resep_data.tgl | date_to_age}}
              {{pasien_detail.PatientName}} </span>-->
              <!-- <br> -->
              <span>
                <a
                  v-if="pasien_detail.Gander"
                  :class="
                    pasien_detail.Gander == 'L' ? 'chip blue' : 'chip pink'
                  "
                >
                  <span style="text-align: center; color: white">{{
                    current_resep_data.tgl | date_to_age
                  }}</span>
                </a>
                {{ pasien_detail.PatientName }}
              </span>
              <br />
              <span class="chip">
                <a class="chip green">
                  <span style="text-align: center; color: white">{{
                    pasien_detail.PatientType == 1
                      ? "Umum"
                      : pasien_detail.PatientType == 2
                      ? "Asuransi"
                      : pasien_detail.PatientType == 3
                      ? "BPJS"
                      : "JKA"
                  }}</span>
                </a>
                {{ current_resep_data.operator }}*
              </span>
              <br />
              <a class="animate__animated animate__flipInX chip white">
                <i v-if="!obat_retur" class="green-text fa fa-check-circle"></i>
                <span>Resep Obat</span>
              </a>
              <a class="animate__animated animate__flipInX chip white">
                <i v-if="obat_retur" class="green-text fa fa-check-circle"></i>
                <span>Resep Retur</span>
              </a>
              <!-- search obat list -->
              <!-- <div v-else class="animate__animated animate__flipInX chip white">
                <input type="text" />
              </div> -->
            </div>
            <div
              class="col row s12 m6 center-align"
              style="max-height: 80px; overflow-y: auto"
            >
              <ul v-for="(res, ind) in reseps_pasien" :key="ind">
                <li
                  :id="'active' + res.NoResep"
                  :class="
                    current_resep_data.nama == res.NoResep
                      ? 'actived'
                      : 'notactived'
                  "
                  @click="get_obat(res)"
                >
                  {{ res.NoResep }} &nbsp;
                </li>
              </ul>
            </div>

            <div class="center-align">
              <!-- {{current_order.NoResep.substr(0,2).toUpperCase()}} -->

              <i class="fa fa-coins">
                <span
                  class="chip green-text white"
                  style="font-weight: bold; font-size: 20px"
                  >{{ totalresep | currency }}</span
                >
              </i>

              <!-- <div class="center-align"> -->
              <i
                v-if="current_order.NoResep.substr(0, 2).toUpperCase() == 'RI'"
                class="fa fa-plus-circle"
                @click="insert_resep()"
              >
                <span class="chip gray green-text"> Tambah Resep </span>
              </i>
              <i
                class="chip white fa fa-save blue-text"
                @click="update_orderdetails(2)"
              >
                Simpan Resep </i
              ><i
                class="chip white fa fa-trash-alt red-text"
                @click="delete_order()"
              >
                hapus Resep
              </i>
              <!-- </div> -->
            </div>
          </li>
        </ul>

        <!-- <span class="title">Nama Pasien</span>
           :
           <span class="title">Title</span>
           
           
           <span class="title">Pharmacist</span>
           :
           <span class="title">Title</span>
           
           <br>
           
            
           <span class="title">Nama Pasien</span>
           :
           <span class="title">Title</span>
           
           
           <span class="title">Pharmacist</span>
           :
           <span class="title">Title</span><br>
          
           <span class="title">Nama Pasien</span>
           :
           <span class="title">Title</span>
           
           <span class="title">Pharmacist</span>
           :
           <span class="title">Title</span><br>
           
           <span class="title">Nama Pasien</span>
           :
        <span class="title">Title</span><br>-->
        <!-- </td>
            <td>
            </td>
           
            <td>
              
            </td>
                </tr>
        </table>-->
      </div>
      <!-- <label>Pilih Resep</label>
      <select class="browser-default" @change="get_obat($event.target.value)">
        <option v-for="(res,ind) in reseps_pasien" :key="ind" :value="res.NoResep">{{res.NoResep}}</option>
      </select>-->
      <div
        class="col m1 s1 l1"
        style="position: fixed; left: 0%; top: 40%; width: 5%"
      >
        <router-link to="/farmasi">
          <i class="fas fa-home">
            <span class="hide-on-small-only show-on-medium-and-up">Utama</span>
          </i>
        </router-link>

        <ul
          v-if="product_types"
          class="table-of-contents animate__animated animate__fadeInLeft animate__delay-1s"
        >
          <li
            v-for="(type, ind) in current_resep"
            :id="'active' + type.OrderTypeID"
            :key="ind"
          >
            <a
              @click="
                scroll_to(type.OrderTypeID);
                change_class(type.OrderTypeID);
              "
            >
              <span class="hide-on-small-only show-on-medium-and-up">
                {{ get_selected_types(type.ProductTypeID) }}
                <!-- <span style="border-radius:10px;padding:2px;background:gray;color:white">{{type.list.length}}</span>  -->
              </span>
              <span class="hide-on-large-only show-on-small-only">{{
                ind + 1
              }}</span>
            </a>
          </li>
        </ul>
        <a @click="save_ordertype({}, 0)">
          <i class="fas fa-plus-square">
            <span class="hide-on-small-only show-on-medium-and-up"
              >Tambah Obat</span
            >
          </i>
        </a>
      </div>
      <div style="width: 95%; margin-left: 5%">
        <table class="striped">
          <tr v-for="(type, ind) in current_resep" :key="ind">
            <td :id="type.OrderTypeID" class="section scrollspy">
              <div
                class="animate__animated animate__fadeInUp list header col row s12"
                style="padding-top: 2px"
              >
                <div class="col row s12">
                  <span
                    class="col s1"
                    @click="
                      type.show = !type.show;
                      change_class(type.OrderTypeID);
                    "
                  >
                    <span>
                      <i
                        v-show="!type.show"
                        class="fa fa-chevron-circle-right animate__animated animate__rotateInUpLeft"
                      ></i>
                      <i
                        v-show="type.show"
                        class="fa fa-chevron-circle-down animate__animated animate__rotateInDownLeft"
                      ></i>
                    </span>
                  </span>
                  <!-- <input class="col s4" type="text" v-model="type.OrderTypeID" /> -->
                  <div class="col s3 input-field">
                    <!-- <dropdown
                      :options="product_types"
                      :selected="get_selected_types(type.ProductTypeID)"
                      v-on:updateOption="type_changed(get_selected_types(type.ProductTypeID),type)"
                    ></dropdown> -->

                    <select
                      v-if="product_types"
                      class="browser-default animate__animated animate__fadeInUp"
                      style
                      v-model="type.ProductTypeID"
                      @change="
                        type_changed(
                          { ProductTypeID: type.ProductTypeID },
                          type
                        )
                      "
                    >
                      <option
                        class="animate__animated animate__fadeInUp"
                        v-for="(ptype, indi) in product_types"
                        :key="indi"
                        :value="ptype.ProductTypeID"
                      >
                        {{ ptype.ProductType }}
                      </option>
                    </select>
                    <!-- <input type="text" @focus="type.show_types=true" v-model="product_types[type.ProductTypeID-1].ProductType" @blur="type.show_types=false" > -->
                    <!-- {{type.ProductType}} -->
                  </div>
                  <div class="col s2" style="margin-top: 20px">
                    <span>
                      <i
                        class="fa fa-pills"
                        style="font-size: 15px; color: #6f33b2"
                      ></i>
                      <span class="chip">{{ type.list.length }}</span>
                    </span>
                  </div>
                  <div class="col s2" style="margin-top: 20px">
                    <span>
                      <i
                        class="fa fa-prescription"
                        style="font-size: 15px; color: #6f33b2"
                      ></i>
                      <span class="chip">{{ type.UangR }}</span>
                    </span>
                  </div>
                  <!-- <div class="col s2" style="margin-top:10px">
                  <span>
                    <i class="fa fa-coin" style="font-size:15px;color:#6F33B2"></i>
                    <span class="chip">
{{type.Harga | currency}}
                    </span>
                  </span>
                  </div>-->
                  <div class="col s2" style="margin-top: 20px">
                    <span>
                      <i
                        class="fa fa-coins"
                        style="font-size: 15px; color: #6f33b2"
                      ></i>
                      <span class="chip">{{ type.TotalHarga | currency }}</span>
                    </span>
                  </div>
                  <div class="col s2" style="margin-top: 20px">
                    <span>
                      <i
                        class="chip fa fa-trash-alt red-text"
                        @click="delete_ordertype(type.OrderTypeID)"
                      >
                      </i>
                      hapus
                    </span>
                  </div>

                  <!-- <a>
                    <i class="fa fa-print" style="font-size:15px;margin-top:15px">&nbsp;Cetak Semua</i>
                  </a>-->
                </div>
                <!-- <div   > -->
                <ul
                  v-if="type.show_types"
                  class="col row s4 collection list"
                  style="margin-top:3%;max-height:150px;overflow-y:auto;position:fixed;background-gray;color:black"
                >
                  <li
                    class="collection-item"
                    v-for="(ptype, indi) in product_types"
                    :key="indi"
                  >
                    {{ ptype.ProductType }}
                  </li>
                </ul>
                <!-- </div> -->
                <transition
                  name="list"
                  leave-active-class="animate__animated animate__fadeInUp"
                  enter-active-class="animate__animated animate__fadeInDown"
                >
                  <ul
                    :class="
                      type.show
                        ? 'animate__animated animate__fadeInUp'
                        : 'animate__animated animate__fadeOut'
                    "
                    v-if="type.show"
                  >
                    <!-- <li>
                  <th>Nama Obat</th>
                  <th>qty</th>
                  <th>list price</th>
                  <th>signa</th>
                    </li>-->
                    <li v-for="(obt, ind_list) in type.list" :key="ind_list">
                      <div
                        class="animate__animated animate__fadeInUp obat col row s12"
                      >
                        <span
                          :class="
                            obt['Status ID'] == 1
                              ? 'ribbon'
                              : obt['Status ID'] == 2
                              ? 'ribbon_i'
                              : ''
                          "
                          >{{
                            obt["Status ID"] == 1
                              ? "Allocated"
                              : obt["Status ID"] == 2
                              ? "Invoiced"
                              : ""
                          }}</span
                        >
                        <div class="col s12 l3">
                          <span class="cell">
                            <input
                              type="text"
                              :disabled="obt['Status ID'] == 2"
                              @mousedown="
                                get_obatlist(obt, obt['Product Name'])
                              "
                              @focus="
                                hide_other_drops(obt, obt['Product Name']);
                                obt.show_list = true;
                                $event.target.select();
                              "
                              @keyup="get_obatlist(obt, obt['Product Name'])"
                              style="color: white"
                              v-model="obt['Product Name']"
                            />
                          </span>
                        </div>
                        <div class="col s12 l9">
                          <div class="col s12 l12">
                            <!-- <span class="cell col s12 l1">
                              <img src="images\eticket.png" style="height:50px;width:50px" alt />
                            </span>-->
                            <span class="cell col s12 l1">
                              <input
                                type="number"
                                v-model="obt.Quantity"
                                @keyup.enter="update_obat_detail(type, obt)"
                                :disabled="
                                  obt.list_obat[0] == undefined
                                    ? true
                                    : obt.list_obat[0].availability < 1
                                    ? true
                                    : false
                                "
                                :max="
                                  obt.list_obat[0]
                                    ? obt.list_obat[0].availability +
                                      (obt.ProductID ==
                                      obt.list_obat[0]['Product ID']
                                        ? (obat_retur ? -1 : 1) * old_qty
                                        : 0)
                                    : 1
                                "
                                min="1"
                                v-on:keyup="
                                  obt.Quantity =
                                    parseInt(obt.Quantity) >
                                    parseInt(obt.list_obat[0].availability)
                                      ? obt.list_obat[0].availability
                                      : parseInt(obt.Quantity) < 1
                                      ? 1
                                      : obt.Quantity
                                "
                                style="color: white"
                              />
                            </span>
                            &nbsp;
                            <span
                              class="cell col s12 l2"
                              style="text-align: center; margin-top: 15px"
                              >{{
                                (obt.Quantity * obt["List Price"]) | currency
                              }}</span
                            >
                            <span class="cell col s12 l2">
                              <input
                                class="cell"
                                type="date"
                                @change="
                                  (event) =>
                                    (obt['Expired'] = event.target.value)
                                "
                                :v-model="
                                  obt['Expired']
                                    ? obt['Expired'].substr(0, 10)
                                    : ''
                                "
                                :value="
                                  obt['Expired']
                                    ? obt['Expired'].substr(0, 10)
                                    : ''
                                "
                              />
                            </span>
                            <span class="cell col s12 l2">
                              <input
                                placeholder="signa"
                                class="cell"
                                type="text"
                                v-model="obt.Signa"
                              />
                            </span>
                            <span class="cell col s12 l2">
                              <input
                                placeholder="dosis"
                                type="text"
                                class="cell"
                                v-model="obt.Dosis"
                              />
                            </span>
                            <div class="hovered">
                              <div class="col row s12 l12">
                                <span
                                  class="col row s12 l4"
                                  style="
                                    text-align: left;
                                    margin-top: 15px;
                                    color: gray;
                                    font-size: 12px;
                                    align: center;
                                  "
                                >
                                  <i class="fa fa-prescription"></i>
                                  &nbsp; {{ obt["List Price"] | currency }}
                                </span>
                                <span class="col row s12 l4">
                                  <!-- <i
                                    class="fa fa-backspace"
                                    v-if="
                                      obt['Status ID'] == 2 ||
                                      obt.return.OrderID
                                    "
                                    @click="add_return(obt, ind, ind_list)"
                                    style="
                                      font-size: 17px;
                                      text-align: center;
                                      margin-top: 15px;
                                      color: #8e44ad;
                                    "
                                    >&nbsp;Retur</i
                                  > -->
                                  <i
                                    class="fa fa-trash-alt"
                                    v-if="obt['Status ID'] != 2"
                                    @click.prevent="delete_obatdetail(obt)"
                                    style="
                                      font-size: 17px;
                                      text-align: center;
                                      margin-top: 15px;
                                      color: gray;
                                    "
                                    >&nbsp;Hapus</i
                                  >
                                </span>
                                <span
                                  class="col row s12 l4"
                                  style="
                                    font-size: 17px;
                                    text-align: center;
                                    margin-top: 12px;
                                    color: #2e86c1;
                                  "
                                >
                                  <i class="fa fa-print">&nbsp;Cetak</i>
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- retun li begins -->
                      <div
                        v-if="obt.return.OrderID"
                        class="animate__animated animate__fadeInUp list return col row s12"
                      >
                        <div class="col s12 l4">
                          <span class="cell">
                            <input
                              type="text"
                              :disabled="true"
                              v-model="obt['Product Name']"
                            />
                          </span>
                        </div>
                        <div class="col s12 l8">
                          <div class="col s12 l12">
                            <span class="cell col s12 l1">
                              <input
                                type="number"
                                style="color: white"
                                :max="Number(obt.Quantity)"
                                min="1"
                                v-on:keyup="
                                  obt.return.Quantity =
                                    parseInt(obt.return.Quantity) >
                                    parseInt(obt.Quantity)
                                      ? obt.Quantity
                                      : parseInt(obt.return.Quantity) < 1
                                      ? 1
                                      : obt.return.Quantity
                                "
                                v-model="obt.return.Quantity"
                                @focus="$event.target.select()"
                              />
                            </span>
                            <span class="cell col s12 l2">{{
                              obt.return["List Price"] | currency
                            }}</span>
                            <!-- <span class="cell col s12 l2">
                              <input class="cell" type="date" :v-model="obt['Expired']" />
                            </span>-->
                            <!-- <span class="cell col s12 l2">
                              <input class="cell" type="text" v-model="obt.Signa" />
                            </span>
                            <span class="cell col s12 l2">
                              <input type="text" class="cell" v-model="obt.Dosis" />
                            </span>-->
                            <span class="cell col s12 l1">
                              <!-- <i class="material-icons cell">delete_forever</i> -->
                              <!-- <img
                                v-if="obt['Status ID']==2"
                                src="images\return.png"
                                style="height:50px;width:50px"
                                alt
                              />-->
                              <img
                                src="images\delete.png"
                                style="height: 50px; width: 50px"
                                @click="delete_obatdetails(obt)"
                              />
                            </span>
                          </div>
                        </div>
                      </div>
                      <!-- retun li ends -->
                      <!-- drop down list begins -->
                      <div
                        class="col row s12"
                        style="transition: all 0.5s linear"
                      >
                        <div v-if="obt.list_obat && obt.show_list">
                          <!-- <span class="chip" @click="get_cur_doc('');dok_search='';show_dok=false">-- Semua --</span> -->
                          <span
                            class="chip right"
                            @click="obt.show_list = false"
                            >X</span
                          >
                        </div>
                        <ul
                          v-if="obt.list_obat && obt.show_list"
                          class="collection"
                        >
                          <li
                            v-for="(obat, ind_ob) in obt.list_obat"
                            :key="ind_ob"
                            class="collection-item"
                            :style="ind_ob == 0 ? 'background:tomato' : ''"
                            @click.prevent="
                              product_selected(type, obt, obat, 'update')
                            "
                          >
                            <div v-if="!obat_retur">
                              <!-- <img :src="'/images/avatar/'+dok.foto" alt class="circle" /> -->
                              <span style="color: black">
                                {{ obat["Product Name"] }}
                                <span class="chip green" style="color: white">{{
                                  Number(obat["Qty Purchased"]) -
                                  Number(obat["QtyReturCstr"]) -
                                  Number(obat["Qty On Hold"]) -
                                  Number(obat["Qty Sold"]) -
                                  Number(obat["QtyMO"])
                                }}</span>
                                --
                                <span class="chip gray"
                                  >{{ obat["Qty On Hold"] }} (Tertahan )</span
                                >
                              </span>
                            </div>
                            <div v-else>
                              <span style="color: black">
                                {{ obat["Product Name"] }}
                                <span class="chip green" style="color: white">{{
                                  Number(obat["Quantity"])
                                }}</span>
                              </span>
                            </div>
                          </li>
                          <li
                            class="collection-item"
                            style="color: black"
                            v-if="obt.list_obat.length == 0"
                          >
                            --tidak ditemukan--
                          </li>
                        </ul>
                      </div>
                      <!-- drop down list ends -->
                    </li>
                    <!-- new obat begins -->
                    <li
                      class="animate__animated animate__fadeInUp list col row s12 new"
                    >
                      <div class="col s12 l4">
                        <span class="cell">
                          <input
                            type="text"
                            @focus="
                              hide_other_drops(
                                type.obt,
                                type.obt['Product Name']
                              );
                              type.obt.show_list = true;
                              $event.target.select();
                            "
                            @keyup="
                              get_obatlist(type.obt, type.obt['Product Name'])
                            "
                            @mousedown="
                              get_obatlist(type.obt, type.obt['Product Name'])
                            "
                            v-model="type.obt['Product Name']"
                          />
                        </span>
                      </div>
                      <!--new  drop down list begins -->
                      <div class="hide-on-med-and-up show-on-small col row s12">
                        <div
                          v-if="type.obt.list_obat && type.obt.show_list"
                          style="
                            position: absolute;
                            z-index: 1000;
                            float: right;
                          "
                        >
                          <span
                            class="chip right transparant"
                            @click="type.obt.show_list = false"
                            >X</span
                          >
                        </div>
                        <ul
                          v-if="type.obt.list_obat && type.obt.show_list"
                          class="collection animate__animated animate__fadeInUp"
                        >
                          <li
                            v-for="(obat, ind_ob) in type.obt.list_obat"
                            :key="ind_ob"
                            class="collection-item animate__animated animate__fadeInUp"
                            :style="ind_ob == 0 ? 'background:tomato' : ''"
                            @click.prevent="
                              product_selected(type, type.obt, obat, 'new');
                              get_obatlist(type.obt, type.obt['Product Name']);
                            "
                          >
                            <!-- <div>
                              <span style="color: black">
                                {{ obat["Product Name"] }}
                                <span class="chip green" style="color: white">{{
                                  Number(obat["Qty Purchased"]) -
                                  Number(obat["QtyReturCstr"]) -
                                  Number(obat["Qty On Hold"]) -
                                  Number(obat["Qty Sold"]) -
                                  Number(obat["QtyMO"])
                                }}</span>
                                --
                                <span class="chip gray"
                                  >{{ obat["Qty On Hold"] }} (Tertahan)</span
                                >
                              </span>
                            </div> -->
                            <div v-if="obat_retur">
                              <!-- <img :src="'/images/avatar/'+dok.foto" alt class="circle" /> -->
                              <span style="color: black">
                                {{ obat["Product Name"] }}
                                <span class="chip green" style="color: white">{{
                                  Number(obat["Qty Purchased"]) -
                                  Number(obat["QtyReturCstr"]) -
                                  Number(obat["Qty On Hold"]) -
                                  Number(obat["Qty Sold"]) -
                                  Number(obat["QtyMO"])
                                }}</span>
                                --
                                <span class="chip gray"
                                  >{{ obat["Qty On Hold"] }} (Tertahan )</span
                                >
                              </span>
                            </div>
                            <div v-else>
                              <span style="color: black">
                                {{ obat["Product Name"] }}
                                <span class="chip green" style="color: white">{{
                                  Number(obat["Quantity"])
                                }}</span>
                              </span>
                            </div>
                          </li>
                          <li
                            class="collection-item"
                            style="color: black"
                            v-if="type.obt.list_obat.length == 0"
                          >
                            --tidak ditemukan--
                          </li>
                        </ul>
                      </div>
                      <!--new obat drop down list ends -->
                      <div class="col s12 l8">
                        <span class="cell col s12 l2">
                          <input
                            type="number"
                            v-model="type.obt.Quantity"
                            @keyup.enter="update_obat_detail(type, type.obt)"
                            :disabled="
                              type.obt.list_obat[0] == undefined
                                ? true
                                : type.obt.list_obat[0].availability < 1
                                ? true
                                : false
                            "
                            :max="
                              type.obt.list_obat[0]
                                ? type.obt.list_obat[0].availability
                                : 1
                            "
                            min="1"
                            v-on:keyup="
                              type.obt.Quantity =
                                parseInt(type.obt.Quantity) >
                                parseInt(type.obt.list_obat[0].availability)
                                  ? type.obt.list_obat[0].availability
                                  : parseInt(type.obt.Quantity) < 1
                                  ? 1
                                  : type.obt.Quantity
                            "
                          />
                        </span>
                        <span class="cell col s12 l2">{{
                          (type.obt.Quantity * type.obt.unit) | currency
                        }}</span>
                        <span class="cell col s12 l2">
                          <input
                            class="cell"
                            type="date"
                            :v-model="text_to_date(type.obt['Expired'])"
                          />
                        </span>
                        <span class="cell col s12 l2">
                          <input
                            class="cell"
                            type="text"
                            v-model="type.obt.Signa"
                          />
                        </span>
                        <span class="cell col s12 l2">
                          <input
                            type="text"
                            class="cell"
                            v-model="type.obt.Dosis"
                          />
                        </span>
                      </div>
                    </li>
                    <!--new  drop down list begins -->
                    <div
                      class="col row s12 hide-on-small-only show-on-medium-and-up"
                    >
                      <div v-if="type.obt.list_obat && type.obt.show_list">
                        <span
                          class="chip right"
                          @click="type.obt.show_list = false"
                          >X</span
                        >
                      </div>
                      <ul
                        v-if="type.obt.list_obat && type.obt.show_list"
                        class="collection animate__animated animate__fadeInUp"
                      >
                        <li
                          v-for="(obat, ind_ob) in type.obt.list_obat"
                          :key="ind_ob"
                          class="collection-item animate__animated animate__fadeInUp"
                          :style="ind_ob == 0 ? 'background:tomato' : ''"
                          @click.prevent="
                            product_selected(type, type.obt, obat, 'new');
                            get_obatlist(type.obt, type.obt['Product Name']);
                          "
                        >
                          <!-- <div>
                            <span style="color: black">
                              {{ obat["Product Name"] }}
                              <span class="chip green" style="color: white">{{
                                Number(obat["Qty Purchased"]) -
                                Number(obat["QtyReturCstr"]) -
                                Number(obat["Qty On Hold"]) -
                                Number(obat["Qty Sold"]) -
                                Number(obat["QtyMO"])
                              }}</span>
                              --
                              <span class="chip gray"
                                >{{ obat["Qty On Hold"] }} (Tertahan)</span
                              >
                            </span>
                          </div> -->
                          <div v-if="!obat_retur">
                            <!-- <img :src="'/images/avatar/'+dok.foto" alt class="circle" /> -->
                            <span style="color: black">
                              {{ obat["Product Name"] }}
                              <span class="chip green" style="color: white">{{
                                Number(obat["Qty Purchased"]) -
                                Number(obat["QtyReturCstr"]) -
                                Number(obat["Qty On Hold"]) -
                                Number(obat["Qty Sold"]) -
                                Number(obat["QtyMO"])
                              }}</span>
                              --
                              <span class="chip gray"
                                >{{ obat["Qty On Hold"] }} (Tertahan )</span
                              >
                            </span>
                          </div>
                          <div v-else>
                            <span style="color: black">
                              {{ obat["Product Name"] }}
                              <span class="chip green" style="color: white">{{
                                Number(obat["Quantity"])
                              }}</span>
                            </span>
                          </div>
                        </li>
                        <li
                          class="collection-item"
                          style="color: black"
                          v-if="type.obt.list_obat.length == 0"
                        >
                          --tidak ditemukan--
                        </li>
                      </ul>
                    </div>
                    <!--new obat drop down list ends -->
                    <!-- new obat ends -->
                  </ul>
                </transition>
              </div>
            </td>
          </tr>
        </table>
      </div>
      <!-- <table> 
        <tr>
          <td
            v-for="(type,ind) in obat"
            :key="ind"
            @click="current_resep=type.list;active_id=type.ProductTypeID"
          >
            <div
              v-show="type.list.length>0"
              :class="active_id==type.ProductTypeID?'chip white-text teal':'chip black-text gray'"
            >
              {{type.ProductType}}
              <span
                class="badge rounded red-text white"
                style="border:0.1px dashed gray;border-radius:20px"
              >{{type.list.length}}</span>
            </div>
          </td>
        </tr>
      </table>-->
    </div>
  </div>
</template>
<script>
import store from "../vuex_store/store";
import dropdown from "vue-dropdowns";
export default {
  components: {
    dropdown,
  },
  data() {
    return {
      totalresep: 0,
      old_qty: 0,
      cur_noreg: "",
      search: {
        show: false,
        org_list: [],
      },
      retur: false,

      show: {
        list_resep: true,
        resep: false,
      },
      current_order: {},
      typingTimer: 0, //timer identifier
      doneTypingInterval: 1000,
      pasien_detail: {},
      resep: {},
      active_id: 1,
      obat: {},
      operator: "",
      current_resep: [],
      list_etickets: [],
      list_obat: [],
      reseps_pasien: [],
      current_resep_data: {
        nama: "",
        operator: "",
        tgl: "",
        orderid: "",
        // user
      },
      active: 0,
    };
  },
  watch: {
    current_resep: function (val) {
      //console.log(val);
      //console.log('watching total getting changed');
      let total = 0;
      this.totalresep = 0;
      this.current_resep.forEach((element) => {
        total = parseFloat(total) + parseFloat(element.TotalHarga);
        this.totalresep = total;
      });
      this.totalresep = total;
    },
  },
  created() {
    window.addEventListener("scroll", this.handleScroll);
  },
  destroyed() {
    window.removeEventListener("scroll", this.handleScroll);
  },
  mounted() {
    let resep = this.$route.params.id.split("_");
    // //////////console.log('hello'+resep);
    // let all_receipts=this.$store.getters.list_resep.list_resep;
    this.active = resep[1].substr(0, 2).toLowerCase() == "rj" ? 0 : 1;
    let res = resep[1];
    let reg = res.substr(0, resep[1].length - 3);
    reg = reg.substr(0, 1).toUpperCase() == "B" ? "" : reg;
    this.cur_noreg = reg;
    this.get_pasien_details({ NoRegistrasi: reg, resep: res });
  },
  computed: {
    obat_retur() {
      let ret = false;
      //console.log(this.current_order.NoResep.substr(0, 1).toUpperCase() != "B");

      if (this.current_order) {
        if (
          this.current_order.NoResep.substr(0, 1).toUpperCase() != "B" &&
          this.current_order.TypeTransaction == "2"
        ) {
          ret = true;
        }
      }
      return ret;
    },
    filter_search_list() {
      let result = [];
      let st = this.search.st;
      this.search.org_list.forEach((element) => {
        if (
          element.nama.toString().toLowerCase().indexOf(st.toLowerCase()) > -1
        ) {
          result.push(element);
        }
      });
      return result;
    },
    user() {
      return this.$store.getters.currentuser;
    },
    product_types() {
      let temp_pt = this.$store.getters.mstr_product_type;
      ////////////////console.log(temp_pt);
      //return;
      if (temp_pt ? temp_pt.length > 0 : temp_pt) {
        this.resep = temp_pt;
        //////////////////console.log(this.resep);
        this.resep.forEach((element) => {
          element["list"] = [];
        });
        return temp_pt;
      }
      return [];
    },
  },
  //   reseps() {
  //     var reseps = [];
  //     axios
  //       .post(
  //         "/api/allresep",
  //         {
  //           resep: this.nama_search,
  //           type: this.active == 0 ? "rj" : "ri",
  //         },
  //         {}
  //       )
  //       .then((response) => {
  //         reseps = response.data;
  //       });
  //     return reseps;
  //   },
  // },
  methods: {
    update_orderdetails(status) {
      axios
        .post("/api/update_orderdetails", {
          orderid: this.current_order["Order ID"],
          status: status,
        })
        .then((response) => {
          this.get_obat(this.current_order);
        });
    },
    update_order(type) {
      axios
        .post("/api/update_order", {
          orderid: this.current_order["Order ID"],
          TypeTransaction: type,
        })
        .then((response) => {});
    },

    delete_order() {
      if (confirm("Apakah Anda yakin hapus resep tsb !")) {
      } else {
        return false;
      }
      axios
        .post("/api/delete_order", { orderid: this.current_order["Order ID"] })
        .then((response) => {
          this.$router.push("/farmasi");

          // this.get_reciepts(
          //   this.current_order.NoResep.substr(
          //     0,
          //     this.current_order.NoResep.length - 3
          //   ),
          //   this.current_order.NoResep.substr(
          //     0,
          //     this.current_order.NoResep.length - 3
          //   ) + "-00"
          // );
        });
    },
    get_totalresep(id = 0) {
      // //console.log("entered to save total");
      // let total = 0;
      // this.totalresep = 0;
      // this.current_resep.forEach((element) => {
      //   total = parseFloat(total) + parseFloat(element.TotalHarga);
      //   this.totalresep = total;
      // });
      // this.totalresep = total;
    },
    get_total_all_resep() {
      let total = 0;
      this.current_resep.forEach((element) => {
        total = parseFloat(total) + parseFloat(element.TotalHarga);
      });

      return total;
    },
    get_selected_types(id) {
      let st = "";
      this.product_types.forEach((element) => {
        if (parseInt(element.ProductTypeID) == parseInt(id)) {
          ////////console.log(element.ProductType);
          // ////////console.log(id);
          ////////console.log("success");
          st = element.ProductType;
        }
      });
      return st;
    },
    type_changed(ptypeid, type) {
      // //////console.log("change event trigered");
      let ptype = {};
      this.product_types.forEach((element) => {
        // //////console.log('list id'+ element.ProductTypeID);
        // //////console.log('compare id'+ JSON.stringify(ptypeid));
        if (
          parseInt(element.ProductTypeID) == parseInt(ptypeid.ProductTypeID)
        ) {
          ptype = element;
          type.UangR = (this.obat_retur ? -1 : 1) * ptype.UangR;
          type.ProductTypeID = ptype.ProductTypeID;
          //console.log(type);

          this.save_ordertype(type, 0);
        }
      });
    },
    delete_ordertype(id) {
      if (confirm("Apakah Anda yakin hapus resep tsb !")) {
      } else {
        return false;
      }
      axios
        .post("/api/delete_ordertype", { OrderTypeID: id })
        .then((response) => {
          this.get_obat(this.current_order);
        });
    },
    save_ordertype(type, state) {
      let harga = 0;
      ////////console.log(type);

      // if (type.list) {

      //console.log(type);
      if (type.list) {
        state = 1;
        // }
        // ////////console.log("begins");
        type.list.forEach((element) => {
          // ////////console.log(harga + " - each is " + element["List Price"]);
          harga =
            parseInt(harga) +
            parseInt(
              parseInt(element["List Price"]) * parseInt(element.Quantity)
            );
        });
      }
      let type_ = {
        Dosis: null,
        ED: null,
        Harga: harga,
        Note1: null,
        Note2: null,
        OrderID: state == 0 ? this.current_resep_data.orderid : type.OrderID,
        OrderTypeID: state == 0 ? null : type.OrderTypeID,
        ProductTypeID: state == 0 ? "1" : type.ProductTypeID,
        Quantity: state == 0 ? "1" : type.list.length,

        TotalHarga: state == 0 ? 0 : parseInt(harga) + parseInt(type.UangR),
        UangR: state == 0 ? "1000" : type.UangR,
        discount: "0.0",
      };
      axios.post("/api/save_ordertype", type_).then((response) => {
        type.TotalHarga=(state == 0) ? 0 : parseInt(harga) + parseInt(type.UangR);
        let cur=this.current_resep;
        this.current_resep=null;
        this.current_resep=cur;
        state == 0 ? this.get_obat(this.current_order) : "";
      });
    },
    text_to_date(text) {
      let date = text.substr(0, 10);
      return date;
    },
    product_selected(type, ref_obt, product, status) {
      // //////////console.log(product);
      ref_obt.ProductID = product["Product ID"];
      ref_obt["Product Name"] = product["Product Name"];
      ref_obt.show_list = false;
      ref_obt.unit = this.obat_retur
        ? product["Unit Price"]
        : this.current_resep_data.nama.substr(0, 2).toUpperCase() == "RJ"
        ? product["list Price Resep"]
        : product["List Price RI"];
      if (status == "update") {
        this.update_obat_detail(type, ref_obt);
      }
    },
    add_return(obat, ind_type, ind_obat) {
      this.current_resep[ind_type].list[ind_obat].return = {
        "List Price": "0",
        OrderID: obat.OrderID,
        OrderTypeID: obat.OrderTypeID,
        "Product Name": "",
        ProductID: "",
        ProductTypeID: obat.ProductTypeID,
        Quantity: "1",
        Signa: null,
        "Unit Satuan": "",
        detail_id: null,
        show_list: false,
      };
      //////////console.log(
      //   "current obat is" +
      //     JSON.stringify(this.current_resep[ind_type].list[ind_obat])
      // );
    },
    update_obat_detail(type, obt) {
      axios
        .post("/api/save_obatdetail", {
          detail: {
            ID: obt.detail_id ? obt.detail_id : null,
            "Order ID": obt.OrderID,
            OrdertypeID: obt.OrderTypeID,
            "Product ID": obt.ProductID,
            Locationstok: 2,
            Quantity: (this.obat_retur ? -1 : 1) * obt.Quantity,
            Dosis: obt.Dosis,
            Signa: obt.Signa,
            ED: obt.Expired,
            "Unit Price": obt.unit,
            Discount: 0,
            "Status ID": 1,
            "Inventory ID": obt["Inventory ID"] ? obt["Inventory ID"] : null,
            Uang_R: null,
            Embalase: null,
          },
          inv: {
            "Transaction ID": obt["Inventory ID"] ? obt["Inventory ID"] : null,
            "Transaction Type": this.obat_retur ? 5 : 2,
            "Product ID": obt.ProductID,
            Quantity: (this.obat_retur ? -1 : 1) * obt.Quantity,
            "Customer Order ID": obt.OrderID,
            LocationInv: this.obat_retur ? 0 : 2,
          },
        })
        .then((response) => {
          if (response.data.error) {
            this.$toastr.e(response.data.error);
          } else {
            // this.save_ordertype(type,1);
            this.get_obat(this.current_order);
          }
          // this.typingTimer=0;
        });
    },
    delete_obatdetail(obt) {
      if (confirm("Apakah Anda yakin hapus obat tsb !")) {
      } else {
        return false;
      }
      axios
        .post("/api/delete_obatdetail", {
          "Inventory ID": obt["Inventory ID"],
          detail_id: obt.detail_id,
        })
        .then((response) => {
          this.get_obat(this.current_order);
        });
    },
    select_obat(obat, ind_type, ind_obat) {
      //////////console.log(ind_type);
      //////////console.log(ind_obat);
      // //////////console.log('obat passed is'+JSON.stringify(obat));
      // //////////console.log('obat jadi'+JSON.stringify(this.current_resep[ind_type]));
      obat.ProductID = 112;
      this.current_resep[ind_type].list[ind_obat].ProductID = 770;
    },
    hide_other_drops(obj, search) {
      this.current_resep.forEach((element) => {
        element.list.forEach((element1) => {
          if (element1.detail_id != obj.detail_id) {
            element1.show_list = false;
          }
        });
      });
      // setSelectionRange(0, search.length);
    },
    get_obatlist(obj, search) {
      //////console.log(search);
      let cur = this.current_order;
      let reg = this.cur_noreg;
      let obat_retur = this.obat_retur;
      if (this.typingTimer) {
        clearTimeout(this.typingTimer);
      }
      this.typingTimer = setTimeout(function () {
        // this.typingTimer=1;
        // if(this.typingTimer==1){
        // ////console.log('status of obat '+this.obat_retur);
        if (obat_retur) {
          if (true) {
            let temp = [];
            axios
              .post("/api/jumlah_obat", { noreg: reg }, {})
              .then((response) => {
                temp = response.data.list_product;
                //////console.log(response.data);

                //////console.log(temp);
                let obj_temp = [];
                temp.forEach((element) => {
                  {
                    let match = 0;
                    obj_temp.forEach((element1) => {
                      if (element1["Product ID"] == element["Product ID"]) {
                        element1["Quantity"] =
                          parseInt(element1["Quantity"]) +
                          parseInt(element["Quantity"]);
                        match = 1;
                      }
                    });
                    if (match == 0) {
                      element.Quantity = parseInt(element.Quantity);
                      obj_temp.push(element);
                    }
                  }
                });
                //////console.log(obj_temp);
                obj.list_obat = [];
                obj_temp.forEach((element) => {
                  // ////console.log(element["Product Name"].includes(search));
                  if (
                    element["Product Name"]
                      .toUpperCase()
                      .includes(search.toUpperCase()) &&
                    obj.list_obat.length < 6
                  ) {
                    element["availability"] = Number(element.Quantity);
                    obj.list_obat.push(element);
                  }
                });
                // obj.list_obat = obj_temp;
              });
          }
        } else {
          axios.post("/api/allstock", { product: search }).then((response) => {
            obj.list_obat = response.data.list_product;
            obj.list_obat.forEach((element) => {
              element["availability"] =
                Number(element["Qty Purchased"]) -
                Number(element["QtyReturCstr"]) -
                Number(element["Qty On Hold"]) -
                Number(element["Qty Sold"]) -
                Number(element["QtyMO"]);
            });
            // this.typingTimer=0;
          });
          // }
        }
      }, 500);
      this.old_qty = obj.Quantity;
    },
    max_alert() {},
    handleScroll(event) {
      var section = document.querySelectorAll(".section");
      var sections = {};
      var i = 0;
      Array.prototype.forEach.call(section, function (e) {
        sections[e.id] = e.offsetTop;
      });
      var scrollPosition =
        document.documentElement.scrollTop || document.body.scrollTop;
      //  ////////////console.log(sections);
      if (sections != undefined) {
        for (i in sections) {
          if (sections[i] <= scrollPosition) {
            // if (scrollPosition >= sections[i]+5 || scrollPosition <= sections[i]-5) {
            ////////////console.log(sections[i]);
            ////////////console.log('scroll is'+scrollPosition);
            if (i) {
              this.change_class(i);
            }
            //  ////////////console.log(sections[sections.length-1]);
          }
        }
      }
    },
    get_obat(reg) {
      this.current_order = reg;
      if (this.obat_retur) {
      }
      axios
        .post(
          "/api/list_obat",
          { noreg: reg.NoResep, orderid: reg["Order ID"] },
          {}
        )
        .then((response) => {
          let list = [];

          // ////////console.log(resep);
          this.resep = [];
          let resep = [];
          this.current_resep = [];
          list = response.data.eticket;
          resep = response.data.ordertypes;
          if (this.product_types.length > 0 && resep.length > 0) {
            this.change_class(resep[0].OrderTypeID);
            resep.forEach((element) => {
              element.list = [];
              element.show = true;
              // element.search = "";
              // element.show_types = false;
              element.obt = {
                Dosis: null,
                Expired: "",
                list_obat: [],
                search: "",
                "List Price": "0",
                unit: 0,
                NoRegistrasi: reg.NoRegistrasi,
                OrderID: element.OrderID,
                OrderTypeID: element.OrderTypeID,
                "Product Name": "",
                ProductID: "",
                ProductTypeID: element.ProductTypeID,
                Quantity: "1",
                Signa: null,
                "Unit Satuan": "",
                detail_id: null,
                show_list: false,
              };
            });
            if (list.length > 0) {
              list.forEach((obat) => {
                obat.maxi = Number(obat.maxi) + Number(obat.Quantity);
                obat.return = {};
                if (resep.length > 0) {
                  let exist = false;
                  resep.forEach((element) => {
                    if (element.OrderTypeID == obat.OrderTypeID) {
                      obat.show_list = false;
                      obat.list_obat = [];
                      element.list.push(obat);
                      exist = true;
                    }
                  });
                }
              });
            }
          }
          this.current_resep = resep;
          this.current_resep_data.nama = reg.NoResep;
          this.current_resep_data.orderid = reg["Order ID"];

          resep.forEach((element) => {
            this.save_ordertype(element, 1);
          });
        });
    },
    isitchecked(id) {
      this.list_etickets.forEach((element) => {
        if (element == id) {
          return true;
        } else {
          return false;
        }
      });
    },
    reflect(id) {
      axios.post("/api/cetak_label", { id: id }, {}).then((response) => {
        M.toast({
          html:
            "Berhasil .. siap untk cetak label ( jika aplikasi e-ticket lagi jalan) !",
        });
      });
      //    if(this.list_etickets.length!=0){
      //     this.list_etickets.forEach((element,ind) => {
      //         if(element!=id){
      //         // ////////////////console.log('not empty');
      //             // ////////////////console.log(this.list_etickets);
      //             // ////////////////console.log(element);
      //             // ////////////////console.log(id);
      //             this.list_etickets.push(id);
      //         }
      //         else if(element==id){
      //             ////////////////console.log("match"+this.list_etickets);
      //             this.list_etickets.splice(ind,1);
      //         }
      //     });
      //    }
      //      else{
      //          ////////////////console.log('empty'+id);
      //             this.list_etickets.push(id);
      //             // ////////////////console.log(this.list_etickets);
      //     }
    },
    get_reciepts(reg, resep) {
      // add api call to get reseps instead of dispatch action
      ////console.log(resep+'- no reg is ');
      axios
        .post("/api/allresep", {
          resep: resep,
          type: reg == "" ? resep.substr(0, 1) : reg.substr(0, 2),
          noreg: reg,
        })
        .then((response) => {
          // //////////console.log(response);
          // if(response.data.list_resep.length==0 || response.data.list_resep[0].)
          if (response.data.list_resep.length == 0) {
            this.insert_resep();
          } else {
            if (resep.slice(-2) == "00") {
              this.get_reciepts_pasien(response.data.list_resep[0]);
            } else {
              // this.insert_resep();
              response.data.list_resep.forEach((element) => {
                ////console.log(resep.substr(0,1).toUpperCase()+'resep first leter')
                if (resep == element.NoResep) {
                  this.get_reciepts_pasien(element);
                }
              });
            }
          }
        });
    },
    insert_resep() {
      let nourut = 1;
      let nomor = 1;
      if (confirm("Apakah resep ini jenis Retur ?")) {
        nomor = 2;
      } else {
        nomor = 1;
      }
      if (this.reseps_pasien.length > 0) {
        nourut =
          parseInt(
            this.reseps_pasien.sort(function (a, b) {
              return parseFloat(b["NoUrut"]) - parseFloat(a["NoUrut"]);
            })[0]["NoUrut"]
          ) + 1;
      }
      ////////console.log("nomor urut" + nourut);
      let resep = {
        "Employee ID": this.user.apotikemp_id,
        Dokter: this.pasien_detail.Doctor_1,
        NoUrut: nourut,
        JenisResep: null,
        NoResep: this.cur_noreg + "-" + ("0" + nourut).slice(-2),
        tglResep: new Date(),
        NoRegistrasi: this.cur_noreg,
        "Customer ID": this.pasien_detail.customer,
        "Order Date": new Date(),
        FinishOrder: null,
        "Shipped Date": new Date(),
        "Status ID": 0,
        TypeTransaction: nomor,
      };
      axios
        .post("/api/insert_order", resep)
        .then((response) => {
          //////console.log("asdfasf");
          //////console.log(response);
          this.get_pasien_details({
            NoRegistrasi: this.cur_noreg,
            resep: this.cur_noreg + "-" + ("0" + nourut).slice(-2),
          });
        })
        .catch((e) => {
          //////console.log(e.message);
        });
    },
    get_pasien_details(reg) {
      ////console.log(reg);
      if (reg.resep.substr(0, 1) == "B") {
        this.get_reciepts(reg.NoRegistrasi, reg.resep);
        this.pasien_detail = {
          Date_of_birth: "",
          Doctor_1: "",
          First_Name: "",
          Gander: "",
          NoMR: "0",
          NoRegistrasi: "-",
          PatientName: "Pasien Bebas",
          PatientType: "",
          customer: "0",
          doc_id: "",
          foto: "",
        };
      } else {
        this.pasien_detail = {};
        axios
          .post(
            "/api/pasien_details",
            {
              noreg: reg.NoRegistrasi,
              type: this.active,
            },
            {}
          )
          .then((response) => {
            this.pasien_detail = response.data.data_pasien[0];
            this.current_resep_data.tgl = this.pasien_detail["Date_of_birth"];
            this.get_reciepts(reg.NoRegistrasi, reg.resep);
          });
      }
    },
    get_reciepts_pasien(reg) {
      // this.internal_direct(
      //   "farmasi/resep/" +
      //     this.$store.getters.randomstring +
      //     "_" +
      //     reg.NoResep +
      //     "_" +
      //     this.$store.getters.randomstring
      // );
      // return true;
      //////////console.log(reg);
      // this.current_order = reg;
      this.current_resep_data.operator = reg["First Name"]
        ? reg["First Name"]
        : ""; //+" "+reg["Last Name"];
      axios
        .post(
          "/api/allresep_per_pasien",
          {
            noreg: reg.NoRegistrasi,
          },
          {}
        )
        .then((response) => {
          this.reseps_pasien = response.data.list_resep_per_pasien;
          if (this.reseps_pasien.length > 0) {
            this.show.list_resep = false;
            this.show.resep = true;
            if (this.active == 1) {
              this.scroll_to("active" + reg.NoResep);
            }
            this.get_obat(reg);
            this.current_resep_data.nama = reg.NoResep;
          }
        });
    },
    internal_direct(path) {
      this.$router.push(path);
    },
    scroll_to(id) {
      // //////////////console.log(id);
      setTimeout(function () {
        document.getElementById(id).scrollIntoView();
      }, 100);
    },
    change_class(id) {
      // //////////////console.log('changed');
      // //////////console.log(id);
      // //////////console.log(this.current_resep);
      this.current_resep.forEach((element) => {
        if (document.getElementById("active" + element.OrderTypeID)) {
          if (element.OrderTypeID == id) {
            document.getElementById("active" + element.OrderTypeID).className =
              "show_hov";
          } else {
            document.getElementById("active" + element.OrderTypeID).className =
              "hide_hov";
          }
        }
      });
    },
  },
};
</script>
<style>
.list {
  padding: 1px;
  /* border: 1px solid gray; */
  border-radius: 10px;
  padding: 8px;
  /* background: white; */
  background-image: linear-gradient(to right, #085078 0%, #85d8ce 100%);
  margin-bottom: 10px;
  color: black;
  overflow: hidden; /* required */
  position: relative;
  transition: height 0.4s;
  -webkit-box-shadow: 1px 10px 15px -1px rgba(43, 18, 43, 1);
  -moz-box-shadow: 1px 10px 15px -1px rgba(43, 18, 43, 1);
  box-shadow: 1px 10px 15px -1px rgba(43, 18, 43, 1);
}
.obat {
  padding: 1px;
  /* border: 1px solid gray; */
  border-radius: 10px;
  padding: 8px;
  /* background: white; */
  background-image: linear-gradient(to right, #085078 0%, #85d8ce 100%);
  margin-bottom: 10px;
  color: black;
  overflow: hidden; /* required */
  position: relative;
  -webkit-box-shadow: 1px 10px 15px -1px rgba(43, 18, 43, 1);
  -moz-box-shadow: 1px 10px 15px -1px rgba(43, 18, 43, 1);
  box-shadow: 1px 10px 15px -1px rgba(43, 18, 43, 1);
}
.hovered {
  width: 0%;
  top: 0;
  right: 0;
  opacity: 0;
  height: 100vmax;
  z-index: 1000;
  position: absolute;
  float: right;
  transition: all 0.5s linear;
}
.obat:hover div.hovered {
  width: 30%;
  background: rgb(238, 238, 237);
  overflow-x: hidden;
  text-align: center;
  white-space: nowrap;
  position: absolute;
  opacity: 1;
  transition: all 0.5s linear;
}
.ribbon {
  margin: 0;
  padding: 0;
  background: rebeccapurple;
  color: white;
  padding: 0.1em 0;
  float: right;
  font-size: 12px;
  position: absolute;
  top: 0;
  right: 0;
  transform: translateX(30%) translateY(0%) rotate(45deg);
  transform-origin: top left;
  -webkit-box-shadow: 2px 17px 25px -3px rgba(43, 18, 43, 1);
  -moz-box-shadow: 2px 17px 25px -3px rgba(43, 18, 43, 1);
  box-shadow: 2px 17px 25px -3px rgba(43, 18, 43, 1);
}
.ribbon_i {
  margin: 0;
  padding: 0;
  background: seagreen;
  color: white;
  padding: 0.1em 0;
  float: right;
  font-size: 12px;
  position: absolute;
  top: 0;
  right: 0;
  transform: translateX(30%) translateY(0%) rotate(45deg);
  transform-origin: top left;
  -webkit-box-shadow: 2px 17px 25px -3px rgba(43, 18, 43, 1);
  -moz-box-shadow: 2px 17px 25px -3px rgba(43, 18, 43, 1);
  box-shadow: 2px 17px 25px -3px rgba(43, 18, 43, 1);
}
.ribbon:before,
.ribbon:after {
  content: "";
  position: absolute;
  top: 0;
  margin: 0 -1px; /* tweak */
  width: 100%;
  height: 100%;
  background: rebeccapurple;
}
.ribbon_i:before,
.ribbon_i:after {
  content: "";
  position: absolute;
  top: 0;
  margin: 0 -1px; /* tweak */
  width: 100%;
  height: 100%;
  background: seagreen;
}
.ribbon:before,
.ribbon_i:before {
  right: 100%;
}
.ribbon:after,
.ribbon_i:after {
  left: 100%;
}
.new {
  background-image: linear-gradient(
    to right,
    #e0eafc 0%,
    #cfdef3 51%,
    #e0eafc 100%
  );
  color: black;
}
.return {
  background-image: linear-gradient(
    to right,
    #ba5370 0%,
    #f4e2d8 51%,
    #ba5370 100%
  );
  color: black;
}
.header {
  padding-top: 10px;
  background-color: #8ec5fc;
  background-image: linear-gradient(62deg, #8ec5fc 0%, #e0c3fc 100%);
}
body {
  background: white;
}
.cell {
  padding: 1%;
  /* box-shadow: inset 0 0 0px 0px black; */
  /* border: 2px solid gray;
    border-radius: 20px; */
  /* background-color: gray; */
  /* color: white; */
  margin-right: 0.31%;
}
.obat:hover {
  padding: 0%;
  -webkit-box-shadow: 2px 17px 25px -3px rgba(43, 18, 43, 1);
  -moz-box-shadow: 2px 17px 25px -3px rgba(43, 18, 43, 1);
  box-shadow: 2px 17px 25px -3px rgba(43, 18, 43, 1);
  background-color: #115fcc;
  /* box-shadow: 0px 24px 48px rgba(0, 0, 0, 0.3); */
  transform: scale(1, 1);
}
.transshow {
  opacity: 1;
  transition: opacity 0.1s ease-out;
  max-height: auto;
  /* transition: max-height 1s ease-in-out; */
}
.transhide {
  max-height: 0px;
  overflow: hidden;
  transition: max-height 0.1s ease-in-out;
}
/* .list-enter,
.list-leave-to {
  visibility: hidden;
  height: 0;
  margin: 0;
  padding: 0;
  opacity: 0;
}
.list-enter-active,
.list-leave-active {
  transition: all 0.1s;
} */
/* .list-enter-active,
.list-leave-active {
  transition: opacity 0.4s;
}
.list-enter, .list-leave-to  {
  opacity: 0;
  transition: opacity 0.4s;
} */
.show_hov {
  left: 0;
  border-left: 1px solid tomato;
}
.hide_hov {
  left: 1px;
  border-left: 0px solid white;
}
.actived {
  /* border: 1px solid green; */
  border-radius: 5px;
  background-image: linear-gradient(
    to right,
    #2c3e50 0%,
    #4ca1af 51%,
    #2c3e50 100%
  );
  color: white;
}
.notactived {
  color: black;
}
</style>