<template>
  <div>
    <transition v-if="show_doktor" name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="chip right" @click="show_doktor=false">X</div>

            <div class="modal-header">
              <slot name="header">Pinda ke Dokter selain dr. {{pasien_placeholder}}</slot>
            </div>

            <div class="modal-body">
              <div name="body">
                <ul class="collection" style="max-height:350px;overflow-y:auto">
                  <li
                    v-for="(dok,ind) in list_doctors_cur_s"
                    :key="ind"
                    class="collection-item avatar"
                    @click.prevent="update_status_visit('Konsul',dok.GroupPerawatan,dok.ID,dok.First_Name);"
                  >
                    <div>
                      <img :src="'/images/avatar/'+dok.foto" alt class="circle" />
                      <span class="title">{{dok.First_Name}}</span>
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <transition v-if="cur_pasiendata&&!show_visit&&!show_dok&&show_soap" name="modal_soap">
      
      <div class="modal_soap-mask">
        <div class="modal_soap-wrapper">
          <div class="modal_soap-container">
            <div class="chip right" @click="show_soap=false">X</div>

            <div class="modal_soap-header">
              <slot name="header">close</slot>
            </div>

            <div class="modal_soap-body">
              <div name="body">
                 <div
      id="options-trigger"
      v-if="show_soap&&!show_dok&&!show_visit"
      class="fixed-action-btn"
      @click="show_float=!show_float"
    >
      <a class="btn-floating btn-large pulse red">
        <!-- <i class="large material-icons">mode_edit</i> -->
        <span v-if="cur_pasiendata">{{cur_pasiendata.Antrian}}</span>
      </a>
    </div>

    <div
      v-if="show_float&&show_soap&&!show_dok&&!show_visit"
      id="options"
      class="fixed-action-btn scale-transition"
      style="margin-bottom:50px"
    >
      <div>
        <div style="margin-bottom:10px" @click="cancel()">
          <div class="chip">Batal</div>
          <a class="btn-floating red">
            <i class="material-icons">cancel</i>
          </a>
        </div>
        <div style="margin-bottom:10px" @click="show_doktor=!show_doktor">
          <div class="chip">Konsul</div>

          <a class="btn-floating yellow darken-1">
            <i class="material-icons">pan_tool</i>
          </a>
        </div>

        <div
          v-if="cur_emr"
          style="margin-bottom:10px"
          @click="update_status_visit('Selesai','','','')"
        >
          <div class="chip">Selsai</div>

          <a class="btn-floating green">
            <i class="material-icons">cloud_done</i>
          </a>
        </div>
        <div v-if="cur_emr" style="margin-bottom:10px" @click="update_emr()">
          <div class="chip">Simpan</div>

          <a class="btn-floating blue">
            <i class="material-icons">save</i>
          </a>
        </div>
      </div>
    </div>
                <div v-if="cur_pasiendata&&!show_visit&&!show_dok&&show_soap">
                  <div v-if="cur_pasiendata">
                    <div class="row">
                      <div class="col s12 m3 l3 center">
                        <div
                          class="s12 chip"
                          :style="cur_pasiendata.Gander=='P'?'background-color:#FB3A55;color:white':'background-color:#3498DB;color:white'"
                        >
                          <img
                            v-if="cur_pasiendata.Gander=='P'"
                            src="/images/common/female.png"
                            alt
                            class="circle responsive-img"
                          />
                          <img
                            v-else
                            src="/images/common/male.png"
                            alt
                            class="circle responsive-img"
                          />

                          <span style="font-weight:bold">{{cur_pasiendata.PatientName}}</span>
                          (
                          {{cur_pasiendata.NoMR}})
                        </div>
                      </div>
                      <div class="col s12 m3 l3 center">
                        <div
                          class="s12 chip"
                        >{{cur_pasiendata.Religion?cur_pasiendata.Religion.toUpperCase():'-'}} - ({{cur_pasiendata["Country/Region"]?cur_pasiendata["Country/Region"].toUpperCase():'-'}})</div>
                      </div>
                      <div class="col s12 m3 l3 center">
                        <div class="s12 chip">
                          <span>dr {{cur_pasiendata?cur_pasiendata.First_Name:''}} - {{count_cur_dok}} /{{list_emr.length}} X</span>
                          <img
                            :src="'/images/avatar/'+cur_pasiendata.foto"
                            alt
                            class="circle responsive-img"
                          />
                        </div>
                      </div>
                      <div class="col s12 m3 l3 center">
                        <div
                          class="s12 chip"
                        >tgl:{{cur_pasiendata.TglKunjungan | formatDate}} - umur:{{cur_pasiendata.Date_of_birth | date_to_age}}</div>
                      </div>
                    </div>
                  </div>

                  <div class="tabs" id="tabs">
                    <div class="tabs-triggers">
                      <div
                        class="tabs-trigger"
                        v-for="(item, index) in categories"
                        :class="[index === active ? 'tabs-trigger--active' : 'tabs-trigger']"
                        :key="index"
                        @click="activate(item.ind)"
                      >
                        <i class="material-icons">{{item.icon}}</i>
                        <span class="center">{{item.name}}</span>
                      </div>
                    </div>
                  </div>

                  <div>
                    <div class="tabs-content" v-if="active === 0">
                      <div class="wrap-collabsible">
                        <input id="collapsible1" class="toggle" type="checkbox" />
                        <label for="collapsible1" class="lbl-toggle">Riwayat berobat sebelumnya</label>
                        <div class="collapsible-content">
                          <div class="content-inner">
                            <table class="striped">
                              <thead>
                                <tr>
                                  <th>Tgl</th>
                                  <th>Keluhan</th>

                                  <th>Anamnesa(S)</th>

                                  <th>TTV</th>
                                  <th>Pemeriksan Fisik</th>

                                  <th>Diagnosa</th>
                                  <th>Rencanatatalaksana(P)</th>

                                  <th>Dokter</th>
                                </tr>

                                <tr v-for="(emr,ind) in list_emr" :key="ind">
                                  <td>{{emr.Tgl | formatDate}}</td>
                                  <td>{{emr.KeluhanPasien}}</td>

                                  <td>{{emr.S_Anamnesa}}</td>
                                  <td>td:{{emr.TekananDarah}} || nadi:{{emr.FrekuensiNadi}} || suhu:{{emr.suhu}} || nafas:{{emr.nafas}} || td:{{emr.TinggiBadan}} || bb:{{emr.BeratBadan}}</td>
                                  <td>{{emr.O_PemeriksaanFisik}}</td>

                                  <td>{{emr.A_Diagnosa}}</td>
                                  <td>{{emr.P_RencanaTatalaksana}}</td>

                                  <td>{{emr.First_Name}}</td>
                                </tr>
                              </thead>
                            </table>
                          </div>
                        </div>
                      </div>

                      <div>
                        <br />
                        <div
                          v-if="cur_emr==null "
                          class="chip"
                          style="background-color:#0A8450;color:white"
                          @click="insert_emr()"
                        >
                          Isi SOAP - Untuk Tgl {{cur_pasiendata?cur_pasiendata.TglKunjungan:'' | formatDate}}
                          <i
                            class="material-icons right"
                          >create</i>
                        </div>
                        <div v-else>
                          <div
                            v-if="show_vital"
                            class="content-inner"
                            style="background:rgba(30, 241, 234, 0.31);border:1px solid transparant;border-radius:10px;"
                          >
                            <div class="row col s12">
                              <div class="input-field col s12 m4 l3">
                                <input
                                  v-model="cur_emr.TekananDarah"
                                  id="td"
                                  type="text"
                                  class="validate"
                                />
                                <label class="active" for="td">Tekanan Darah</label>
                              </div>
                              <div class="input-field col s12 m4 l3">
                                <input
                                  v-model="cur_emr.SkorNyeri"
                                  id="sn"
                                  type="text"
                                  class="validate"
                                />
                                <label class="active" for="sn">Skor Nyeri</label>
                              </div>
                              <div class="input-field col s12 m4 l3">
                                <input
                                  v-model="cur_emr.BeratBadan"
                                  id="bb"
                                  type="text"
                                  class="validate"
                                />
                                <label class="active" for="bb">Berat Badan</label>
                              </div>
                              <div class="input-field col s12 m4 l3">
                                <input
                                  v-model="cur_emr.AlatBantu"
                                  id="ab"
                                  type="text"
                                  class="validate"
                                />
                                <label class="active" for="ab">Alat Bantu</label>
                              </div>

                              <div class="input-field col s12 m4 l3">
                                <input
                                  v-model="cur_emr.FrekuensiNafas"
                                  id="fnf"
                                  type="text"
                                  class="validate"
                                />
                                <label class="active" for="fnf">Frequensi Nafas</label>
                              </div>
                              <div class="input-field col s12 m4 l3">
                                <input v-model="cur_emr.Suhu" id="su" type="text" class="validate" />
                                <label class="active" for="su">Suhu</label>
                              </div>
                              <div class="input-field col s12 m4 l3">
                                <input
                                  v-model="cur_emr.TinggiBadan"
                                  id="tb"
                                  type="text"
                                  class="validate"
                                />
                                <label class="active" for="tb">Tinggi Badan</label>
                              </div>
                              <div class="input-field col s12 m4 l3">
                                <input
                                  v-model="cur_emr.Prothesa"
                                  id="pr"
                                  type="text"
                                  class="validate"
                                />
                                <label class="active" for="pr">Prothesa</label>
                              </div>

                              <div class="input-field col s12 m4 l3">
                                <input
                                  v-model="cur_emr.FrekuensiNadi"
                                  id="fn"
                                  type="text"
                                  class="validate"
                                />
                                <label class="active" for="fn">Frequensi Nadi</label>
                              </div>
                              <div class="input-field col s12 m4 l3">
                                <input
                                  v-model="cur_emr.ResikoJatuh"
                                  id="srj"
                                  type="text"
                                  class="validate"
                                />
                                <label class="active" for="srj">Skor Resiko Jatuh</label>
                              </div>
                              <div class="input-field col s12 m4 l3">
                                <input
                                  v-model="cur_emr.LingkarKepala"
                                  id="lk"
                                  type="text"
                                  class="validate"
                                />
                                <label class="active" for="lk">Lingkar Kepala</label>
                              </div>
                              <div class="input-field col s12 m4 l3">
                                <input
                                  v-model="cur_emr.CacatTubuh"
                                  id="ct"
                                  type="text"
                                  class="validate"
                                />
                                <label class="active" for="ct">CacatTubuh</label>
                              </div>

                              <div class="input-field col s12">
                                <input
                                  v-model="cur_emr.RiwayatJatuh"
                                  id="rj"
                                  type="text"
                                  class="validate"
                                />
                                <label class="active">Riwayat Jatuh</label>
                              </div>

                              <!-- Status Psikologis -->
                              <div class="col s12">
                                <label class="active">Status Psikologis</label>
                              </div>
                              <div class="row">
                                <div class="switch col s12 m3 l3">
                                  <label class="active">
                                    Bahasa
                                    <input
                                      type="checkbox"
                                      v-model="cur_emr.HE_bahasa"
                                      :checked="cur_emr.HE_bahasa!=0"
                                    />
                                    <span class="lever"></span>
                                  </label>
                                </div>

                                <div class="switch col s12 m3 l3">
                                  <label class="active">
                                    cacat/Fisik/Kognitif/ (Gangguan pengilihatan / pendengaran)
                                    <input
                                      type="checkbox"
                                    />
                                    <span class="lever"></span>
                                  </label>
                                </div>
                                <div class="input-field col s12 m3 l3">
                                  <input
                                    v-model="cur_emr.StatusPsikologis"
                                    id="ll"
                                    type="text"
                                    class="validate"
                                  />
                                  <label class="active" for="ll">Lain-lain</label>
                                </div>
                              </div>

                              <!-- hambatan edukasi -->

                              <div class="col s12">
                                <label class="active">Hambatan Edukasi</label>
                              </div>
                              <div class="row">
                                <div class="switch col s12 m3 l3">
                                  <label class="active">
                                    Depresi
                                    <input type="checkbox" />
                                    <span class="lever"></span>
                                  </label>
                                </div>

                                <div class="switch col s12 m3 l3">
                                  <label class="active">
                                    Takut
                                    <input type="checkbox" />
                                    <span class="lever"></span>
                                  </label>
                                </div>
                                <div class="switch col s12 m3 l3">
                                  <label class="active">
                                    Agresif
                                    <input type="checkbox" />
                                    <span class="lever"></span>
                                  </label>
                                </div>
                                <div class="switch col s12 m3 l3">
                                  <label class="active">
                                    Melukai diri Sendiri/Orang Lain
                                    <input
                                      type="checkbox"
                                      checked="true"
                                    />
                                    <span class="lever"></span>
                                  </label>
                                </div>
                              </div>
                              <div class="input-field col s12">
                                <input
                                  v-model="cur_emr.KeluhanPasien"
                                  id="kelp"
                                  type="text"
                                  class="validate"
                                />
                                <label class="active" for="kelp">Keluhan Pasien</label>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div
                              v-if="!show_vital"
                              class="col s12"
                              style="background:rgba(64, 201, 169, 0.17);border:1px solid transparant;border-radius:10px;"
                            >
                              <div class="col s12">
                                <div class="input-field col s3 m3 l3">
                                  <label class="active" for="td2">TD</label>
                                  <input
                                    v-model="cur_emr.TekananDarah"
                                    id="td2"
                                    type="text"
                                    class="validate"
                                  />
                                </div>
                                <div class="input-field col s3 m3 l3">
                                  <input
                                    v-model="cur_emr.FrekuensiNadi"
                                    id="fn2"
                                    type="text"
                                    class="validate"
                                  />
                                  <label class="active" for="fn2">Nadi</label>
                                </div>
                                <div class="input-field col s3 m3 l3">
                                  <input
                                    v-model="cur_emr.Suhu"
                                    id="suhu2"
                                    type="text"
                                    class="validate"
                                  />
                                  <label class="active" for="suhu2">Suhu</label>
                                </div>
                                <div class="input-field col s3 m3 l3">
                                  <input
                                    v-model="cur_emr.FrekuensiNafas"
                                    id="fnf2"
                                    type="text"
                                    class="validate"
                                  />
                                  <label class="active" for="fnf2">Nafas</label>
                                </div>
                              </div>
                              <div class="col s12">
                                <div class="input-field col s3 m3 l3">
                                  <input
                                    v-model="cur_emr.TinggiBadan"
                                    id="tb2"
                                    type="text"
                                    class="validate"
                                  />
                                  <label class="active" for="tb2">TB</label>
                                </div>
                                <div class="input-field col s3 m3 l3">
                                  <input
                                    v-model="cur_emr.BeratBadan"
                                    id="bb2"
                                    type="text"
                                    class="validate"
                                  />
                                  <label class="active" for="bb2">BB</label>
                                </div>
                                <div class="col s2 m1 l1" @click="update_alergi()">
                                  <i class="btn-floating pulse pink">Alergi</i>
                                </div>
                                <div class="col s4 m5 l5">
                                  <div class="input-field" v-if="cur_pasiendata">
                                    <!-- <p class="btn-floating pulse red"> -->

                                    <!-- </p> -->
                                    <input
                                      id="alergi"
                                      type="text"
                                      class="validate"
                                      autocomplete="off"
                                      v-model="cur_pasiendata.Alergi"
                                    />
                                    <label class="active" for="alergi">Riwayat</label>
                                  </div>

                                  <!-- <textarea id="textareas" v-model="cur_emr.S_Anamnesa" ></textarea> -->
                                  <!-- <textarea-autosize
                    placeholder="Riwayat Alergi"
                    ref="myTextarea"
                    v-model="cur_pasiendata.Alergi"
                    :min-height="20"
                    :max-height="550"
                                  />-->
                                </div>
                              </div>
                            </div>
                            <div class="col s12">
                              <label class="active">
                                <input type="checkbox" @click="show_vital=!show_vital" />
                                <span>lihat full vital sign</span>
                              </label>
                            </div>
                            <div class="col s12">
                              <div class="col s12 m6 l6">
                                <label class="active" for="textareas">Anamnesa ( S )</label>
                                <!-- <textarea id="textareas" v-model="cur_emr.S_Anamnesa" ></textarea> -->
                                <textarea-autosize
                                  placeholder="Type something here..."
                                  ref="myTextarea"
                                  v-model="cur_emr.S_Anamnesa"
                                  :min-height="150"
                                  :max-height="550"
                                />
                              </div>
                              <div class="col s12 m6 l6">
                                <label
                                  class="active"
                                  for="textareap"
                                >RencanaTatalaksana & Therapi ( P )</label>
                                <div
                                  class="chip green text-white"
                                  @click="copy_obat()"
                                  v-if="list_obat"
                                >copy-obat</div>
                                <!-- <textarea id="textareap"  v-model="cur_emr.P_RencanaTatalaksana"></textarea> -->
                                <textarea-autosize
                                  placeholder="Type something here..."
                                  ref="myTextarea"
                                  v-model="cur_emr.P_RencanaTatalaksana"
                                  :min-height="150"
                                  :max-height="350"
                                />
                              </div>
                              <div class="col s12 m6 l6">
                                <label class="active" for="textareao">PemeriksaanFisik ( O )</label>
                                <!-- <textarea id="textareao"  v-model="cur_emr.O_PemeriksaanFisik"></textarea> -->
                                <textarea-autosize
                                  placeholder="Type something here..."
                                  ref="myTextarea"
                                  v-model="cur_emr.O_PemeriksaanFisik"
                                  :min-height="150"
                                  :max-height="350"
                                />
                              </div>
                              <div class="col s12 m6 l6">
                                <label class="label_mo" for="textaread">Diagnosa ( A )</label>
                                <textarea-autosize
                                  placeholder="Type something here..."
                                  ref="myTextarea"
                                  v-model="cur_emr.A_Diagnosa"
                                  :min-height="150"
                                  :max-height="350"
                                />
                              </div>

                              <!-- diagnosa icd 10 -->

                              <div class="input-field col s12">
                                <input
                                  id="diag"
                                  type="text"
                                  class="validate"
                                  value="diag_search"
                                  autocomplete="off"
                                  placeholder="cari Diagnosa ICD10.."
                                  v-model="diag_search"
                                  @focus="show_diag=true;diag_search=''"
                                />
                              </div>

                              <ul
                                v-if="master_diag&&show_diag"
                                class="collection"
                                style="max-height:250px; overflow-y: auto;"
                              >
                                <li
                                  class="chip left"
                                  style="position: -webkit-sticky;
       position: sticky;
        top: 0;"
                                  @click="show_diag=false"
                                >X</li>
                                <li
                                  v-for="(diag,ind) in master_diag"
                                  :key="ind"
                                  class="collection-item"
                                  @click.prevent="insert_icd10(cur_pasiendata.NoRegistrasi,diag);diag_search=diag.code2;show_diag=false"
                                >
                                  <div>
                                    <span class="title">{{diag.code2}} ({{diag.description}})</span>
                                  </div>
                                </li>
                              </ul>

                              <!-- list diagnosa entry -->
                              <table
                                class="striped highlight"
                                style="left:0;border:2px solid gray;border-radius:10%;"
                              >
                                <tbody v-if="list_diag">
                                  <tr v-for="(diag,inde) in list_diag" :key="inde">
                                    <td>{{diag.Code_Diag}}</td>
                                    <td>{{diag.Desc_Diag}}</td>
                                    <td>
                                      <i
                                        class="material-icons"
                                        @click="delete_icd10(diag.id,diag.NoRegistrasi)"
                                      >delete_forever</i>
                                    </td>
                                  </tr>
                                </tbody>
                                <tr v-if="!list_diag">
                                  <td></td>
                                  <td colspan="4">-- kosong --</td>
                                </tr>
                              </table>

                              <!-- procedure idc 9 -->

                              <div class="input-field col s12">
                                <input
                                  id="proc"
                                  type="text"
                                  class="validate"
                                  value="proc_search"
                                  autocomplete="off"
                                  placeholder="cari Procedur ICD9.."
                                  v-model="proc_search"
                                  @focus="show_proc=true;proc_search=''"
                                />
                              </div>

                              <ul
                                v-if="master_proc&&show_proc"
                                class="collection"
                                style="max-height:250px; overflow-y: auto;"
                              >
                                <li
                                  class="chip left"
                                  style="position: -webkit-sticky;
        position: sticky;
      top: 0;"
                                  @click="show_proc=false"
                                >X</li>
                                <li
                                  v-for="(proc,ind) in master_proc"
                                  :key="ind"
                                  class="collection-item"
                                  @click.prevent="insert_icd9(cur_pasiendata.NoRegistrasi,proc);proc_search=proc.Code2;show_proc=false"
                                >
                                  <div>
                                    <span class="title">{{proc.Code2}} ({{proc.Description}})</span>
                                  </div>
                                </li>
                              </ul>

                              <!-- list procedur entry -->
                              <table
                                class="striped highlight"
                                style="left:0;border:2px solid gray;border-radius:10%;"
                              >
                                <tbody v-if="list_procedure">
                                  <tr v-for="(proc,inde) in list_procedure" :key="inde">
                                    <td>{{proc.Code_Proc}}</td>
                                    <td>{{proc.Desc_Proc}}</td>
                                    <td>
                                      <i
                                        class="material-icons"
                                        @click="delete_icd9(proc.ID,proc.NoRegistrasi)"
                                      >delete_forever</i>
                                    </td>
                                  </tr>
                                </tbody>
                                <tr v-if="!list_procedure">
                                  <td></td>
                                  <td colspan="4">-- kosong --</td>
                                </tr>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- <table v-if="categories[0].list[0]" class="striped">
             <thead>
          <tr>
              <th>Nama Dokter</th>
              <th>Jumlah</th>
              

          </tr>
        </thead>
        <tr v-for="(item,ind) in categories[0].list" :key="ind">
            <td>dr {{item.First_Name}}, <span class="badge"> {{item.Spesialis}} </span> </td>
            <td>{{item.CountOfID}}</td>
        </tr>
                      </table>-->
                    </div>
                    <div class="tabs-content" v-if="active === 1">
                      <!-- procedure idc 9 -->
                      <div class="input-field col s2">
                        <input
                          id="proc"
                          type="number"
                          class="validate"
                          value="tarif_rwj_qty"
                          autocomplete="off"
                          placeholder="qty"
                          v-model="tarif_rwj_qty"
                          @focus="show_tarif_rwj=true;tarif_rwj_search=''"
                        />
                      </div>
                      <div class="input-field col s10">
                        <input
                          id="tindakan_visit"
                          type="text"
                          class="validate"
                          value="tarif_rwj_search"
                          autocomplete="off"
                          placeholder="cari Tindakan / Visit"
                          v-model="tarif_rwj_search"
                          @focus="show_tarif_rwj=true;tarif_rwj_search=''"
                          @mousedown="show_tarif_rwj=true"
                        />
                      </div>
                      <ul
                        v-if="tarif_rwj&&show_tarif_rwj"
                        class="collection"
                        style="max-height:250px; overflow-y: auto;"
                        @blur="show_tarif_rwj=false"
                      >
                        <li
                          class="chip left"
                          style="position: -webkit-sticky;
     position: sticky;
      top: 0;"
                          @click="show_tarif_rwj=false"
                        >X</li>
                        <li
                          v-for="(tarif,ind) in tarif_rwj_d"
                          :key="ind"
                          class="collection-item"
                          @click.prevent="insert_visit_detail(tarif);tarif_rwj_search=tarif['Product Name'];show_tarif_rwj=false"
                        >
                          <div>
                            <span
                              class="title"
                            >{{tarif["CategoryProduct"]}}- ({{tarif["Product Name"]}} - RP. ({{tarif["TarifRS"]}})</span>
                          </div>
                        </li>
                      </ul>

                      <!-- list procedur entry -->
                      <table
                        class="striped highlight"
                        style="left:0;border:2px solid gray;border-radius:10%;"
                      >
                        <tbody v-if="list_rj_tindakan">
                          <tr v-for="(visit,inde) in list_rj_tindakan" :key="inde">
                            <td>{{visit["NamaProduct"]}}</td>
                            <td>Rp. {{visit["Unit Price"]}}</td>
                            <td>
                              <i
                                class="material-icons"
                                @click="delete_rwj_tindakan(visit.ID)"
                              >delete_forever</i>
                            </td>
                          </tr>
                        </tbody>
                        <tr v-if="!list_procedure">
                          <td></td>
                          <td colspan="4">-- kosong --</td>
                        </tr>
                      </table>
                    </div>

                    <div class="tabs-content" v-if="active === 2">--blm</div>
                    <div class="tabs-content" v-if="active === 3">--blm</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>

   
    <div class="col s12">
      <div class="input-field col l3 s6 m6">
        <i class="prefix">dr.</i>
        <input
          id="dok"
          name="dok"
          type="text"
          autocomplete="off"
          :disabled="disable_dok"
          :placeholder="''+pasien_placeholder=='dr'?'Cari Dokter':pasien_placeholder"
          v-model="dok_search"
          @mousedown="show_dok=true;show_visit=false;dok_search=''"
          @focus="show_dok=true;show_visit=false;dok_search=''"
        />
      </div>
      <div class="input-field col l9 s6 m6">
        <!-- <i class="material-icons prefix" @click="internal_direct()">home</i> -->
        <input
          id="nama"
          name="nama"
          type="text"
          class="validate"
          :placeholder="'Cari Pasien dr. '+pasien_placeholder"
          autocomplete="off"
          v-model="nama_search"
          @mousedown="onsearchpatient"
          @focus="onsearchpatient"
        />
        <!-- <label class="active"  for="nama">cari Pasien dr. {{cur_pasiendata.First_Name}}</label> -->
      </div>
    </div>

    <div v-if="list_doctors_cur&&show_dok">
      <span class="chip" @click="get_cur_doc('');dok_search='';show_dok=false">-- Semua --</span>

      <span class="chip right" @click="show_dok=false">X</span>
    </div>
    <ul v-if="list_doctors_cur&&show_dok" class="collection">
      <li
        v-for="(dok,ind) in list_doctors_cur"
        :key="ind"
        class="collection-item avatar"
        @click.prevent="get_cur_doc(dok);"
      >
        <div>
          <img :src="'/images/avatar/'+dok.foto" alt class="circle" />
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

    <div v-if="list_allvisit_a&&show_visit">
      <!-- <div class="chip" @click="sort_antri()">
          <i class="material-icons">sort</i>
          <span>Antrian</span>
      </div>-->

      <span class="chip right" @click="show_visit=false">X</span>
    </div>

    <table
      v-if="list_allvisit_a&&show_visit"
      class="table striped highlight"
      style="left:0;max-height:250px; overflow-y: auto;"
    >
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
          @click.prevent="reload(visit.NoRegistrasi,visit.PatientName)"
        >
          <td>{{visit.PatientName | capitalize}}</td>

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
          <td>
            <span
              class="badge white"
              style="background-image:url('/images/common/soap.png')"
            >{{visit['Antrian']}}</span>
            <!-- <i v-if="visit.Selesai==1" class="material-icons">check_box</i>
            <i v-else class="material-icons">check_box_outline_blank</i>-->
          </td>
        </tr>
      </tbody>
      <tr v-if="list_allvisit_d?list_allvisit_d.length==0:!list_allvisit_d">
        <td></td>
        <td colspan="4">-- tidak ada Pasien --</td>
        <td></td>
      </tr>
    </table>

    <div v-if="!show_soap || show_visit || show_dok">
      <img class="responsive-img" src="/images/common/rwjbg.gif" alt />
    </div>
  </div>
</template>

<script>
import axios_rstb from "axios";
export default {
  data() {
    return {
      user: {},
      disable_dok: false,
      active: 0,
      show_float: false,
      start_date: "",
      end_date: "",
      count_cur_dok: 0,
      tarif_rwj_qty: 1,
      nama_search: "",
      diag_search: "",
      dok_search: "",
      proc_search: "",
      tarif_rwj_search: "",
      show_tarif_rwj: false,
      show_soap: false,
      show_visit: true,
      show_dok: false,
      show_doktor: false,
      show_diag: false,
      show_proc: false,
      show_vital: false,
      cur_emr: null,
      list_obat: [],
      pasien_placeholder: "",
      categories: [
        { ind: 0, name: "konsul", icon: "nature_people", list: [] },
        { ind: 1, name: "input jasa doc", icon: "layers", list: [] },

        { ind: 2, name: "Resep Online", icon: "local_pharmacy", list: [] },
        { ind: 3, name: "lab", icon: "colorize", list: [] }
      ],
      list_procedure: [],
      list_diag: [],
      list_rj_tindakan: [],
      asc: 1,
      cur_doc_id: "",
      orginal_emr: null
    };
  },
  mounted() {
    ////console.log("mounted");
    this.user = this.$store.getters.currentuser;
    if (this.user.title == "Doktor") {
      this.cur_doc_id = parseInt(this.user.head);
      this.disable_dok = true;
      this.pasien_placeholder = this.user.name;
    }
    this.cur_emr = null;
    this.count_cur_dok = 0;
    this.$store.dispatch("get_allvisit", {
      search: this.nama_search,
      dok: this.cur_doc_id
    });
    if (this.$route.params.id != "") {
      // this.$store.dispatch("get_list_obat",{"noreg":this.$route.params.id+'-01'});

      this.$store.dispatch("get_pasiendata_noreg", {
        noreg: this.$route.params.id
      });
      axios
        .post("/api/get_rwj_tindakan", {
          no: this.$route.params.id
        })
        .then(response => {
          this.list_rj_tindakan = response.data.list_visit_details;
        });
    }
    // this.$store.dispatch("master_rwj");
    // this.$store.dispatch("master_proc");
  },
  computed: {
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
    },
    list_doctors_cur_s() {
      if (this.list_doctors.length > 0) {
        let st = this.dok_search;
        let result = [];

        this.list_doctors.forEach(element => {
          // console.log(element.PatientName);
          if (element.Spesialis && this.cur_pasiendata.Doctor_1 != element.ID) {
            result.push(element);
          }
        });
        return result;
      }
    },
    diag() {
      return this.$store.getters.get_mstr_diag;
    },
    proc() {
      return this.$store.getters.get_mstr_proc;
    },
    master_diag() {
      ////console.log("diag");
      if (this.diag.length > 0) {
        let st = this.diag_search;
        let result = [];
        if (this.diag_search.length < 1) {
          return this.diag.slice(0, 30);
        } else {
          this.diag.forEach(element => {
            //////console.log(element.PatientName);
            if (
              element.code2
                .toString()
                .toLowerCase()
                .indexOf(st) > -1 ||
              element.description
                .toString()
                .toLowerCase()
                .indexOf(st.toLowerCase()) > -1
            ) {
              result.push(element);
            }
          });
          return result.slice(0, 30);
        }
      }
    },
    master_proc() {
      if (this.proc.length > 0) {
        let st = this.proc_search;
        let result = [];
        if (this.proc_search.length == 0) {
          return this.proc;
        } else {
          this.proc.forEach(element => {
            //////console.log(element.PatientName);
            if (
              element.Code2.toString()
                .toLowerCase()
                .indexOf(st) > -1 ||
              element.Description.toString()
                .toLowerCase()
                .indexOf(st) > -1
            ) {
              result.push(element);
            }
          });
          return result;
        }
      }
    },
    tarif_rwj() {
      // //console.log('entered into emr rwj');
      let rwj = this.$store.getters.get_mstr_tarif_rj;
      // let list = [];
      // // //console.log(rwj);
      // rwj.forEach(element => {
      //   if (element.CodeUNIT == "UNIT" + this.cur_pasiendata.GroupPerawatan) {
      //     list.push(element);
      //   }
      // });
      return rwj;
    },
    tarif_rwj_d() {
      ////console.log("diag");
      if (this.tarif_rwj.length > 0) {
        let st = this.tarif_rwj_search;
        let result = [];
        if (this.tarif_rwj_search.length < 1) {
          return this.tarif_rwj.slice(0, 30);
        } else {
          this.tarif_rwj.forEach(element => {
            //////console.log(element.PatientName);
            if (
              element["Product Name"]
                .toString()
                .toLowerCase()
                .indexOf(st.toLowerCase()) > -1
            ) {
              result.push(element);
            }
          });
          return result.slice(0, 30);
        }
      }
    },
    list_allvisit_a() {
      return this.$store.getters.get_allvisit;
    },
    list_allvisit_d() {
      if (this.list_allvisit_a.length > 0) {
        let st = this.nama_search;
        let result = [];
        if (this.nama_search.length == 0) {
          return this.list_allvisit_a;
        } else {
          this.list_allvisit_a.forEach(element => {
            //////console.log(element.PatientName);
            if (
              element.Antrian == st ||
              element.PatientName.toString()
                .toLowerCase()
                .indexOf(st.toString().toLowerCase()) > -1 ||
              element.NoMR.toString()
                .toLowerCase()
                .indexOf(st.toLowerCase()) > -1 ||
              element.ID.indexOf(st) > -1
            ) {
              result.push(element);
            }
          });
          return result;
        }
      }
    },
    token() {
      // console.log(this.$store.getters.get_mstr_rstb_api_token);
      return this.$store.getters.get_mstr_rstb_api_token;
    },
    cur_pasiendata() {
      //////console.log('ennndf');
      let cur = this.$store.getters.get_rwj_pasiendata;
      if (cur) {
        // console.log(this.token);
        this.show_soap = true;
        this.show_float = false;
        ////console.log("repeated");
        this.get_icd10(cur.NoRegistrasi);
        this.get_icd9(cur.NoRegistrasi);
        this.nama_search = cur.PatientName;
        // this.cur_doc_id!=''?
        // this.pasien_placeholder = cur.First_Name:'-semua-';

        // this.$store.dispatch("get_allvisit", { search: "", dok: this.cur_doc_id });
        this.$store.dispatch("get_all_emr", { mr: cur.NoMR });
        return this.$store.getters.get_rwj_pasiendata;
      }
    },
    list_emr() {
      let cur1 = this.$store.getters.get_rwj_list_emr;
      this.cur_emr = null;
      let cur;
      // //console.log('cur is'+cur.length);
      if (cur1) {
        cur = cur1.list_emr;

        this.count_cur_dok = 0;
        // this.list_emr=[];
        cur.forEach((element, ind) => {
          if (element.Doctor_1 == this.cur_pasiendata.Doctor_1) {
            this.count_cur_dok = this.count_cur_dok + 1;
          }
          if (element.NoRegistrasi == this.cur_pasiendata.NoRegistrasi) {
            this.cur_emr = element;
            // this.orginal_emr=new Object(element);
          }
          // console.log(ind);
          // console.log(cur.length);
          // if(ind==cur.length-1&&cur.length>0&&this.cur_pasiendata&&!this.cur_emr){
          //   this.insert_emr();
          // }
        });
        // if(cur1.inserted!=null){
        //   if(!cur1.inserted){
        //     this.insert_emr();
        //   }
        // }
        //  if(this.cur_emr==null && cur.length>0){
        //   this.insert_emr();
        // }
        //console.log("cur emr is " + this.cur_emr);
        //this.cur_emr=cur;
        return cur;
      }
    }
    // list_obat() {
    //   return this.$store.getters.list_obat;
    // }
  },
  methods: {
    onsearchpatient() {
      // console.log(_.isEqual(this.cur_emr, this.orginal_emr));
      // if(!_.isEqual(this.cur_emr, this.orginal_emr)){
      //   var r=confirm('Data Belum diSimpan "Ok" to Simpan ');
      //   if(r){
      if (!this.show_visit && this.cur_emr) {
        this.update_emr("no");
      }
      //   }
      // }
      this.show_float = false;

      this.show_visit = true;
      this.show_dok = false;
      this.nama_search = "";
    },
    get_cur_doc(dok) {
      if (dok != "") {
        this.pasien_placeholder = dok.First_Name;
        this.dok_search = dok.First_Name;
        if (dok.ID != this.cur_doc_id) {
          this.cur_emr = null;
          this.show_soap = false;
        } else {
          this.show_soap = true;
        }
        this.cur_doc_id = dok.ID;
        //console.log(dok);
      } else {
        this.cur_doc_id = "";
        this.pasien_placeholder = "--Semua--";
      }
      this.show_dok = false;

      this.show_visit = true;
      this.get_allvisit("dok");
    },
    copy_obat() {
      let temp = "";
      this.list_obat.forEach(element_res => {
        element_res.forEach(element => {
          temp = temp + "" + element["Product Name"] + "\n";
        });
      });
      this.cur_emr.P_RencanaTatalaksana =
        temp + "\n" + this.cur_emr.P_RencanaTatalaksana;
    },
    sort_antri() {
      this.asc = this.asc * -1;
      this.list_allvisit_d.sort((t1, t2) =>
        parseInt(t1.Antrian) < parseInt(t2.Antrian) ? this.asc : -this.asc
      );
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

    sub(str) {
      return str.substr(-3);
    },
    update_status_visit(status, newunit, newid, namadok) {
      if (status == "Konsul") {
        let kon = confirm(
          "Apakah anda yakin pasien ini dikonsul ke dr. " + namadok
        );
        if (!kon) {
          return;
        }

        this.show_doktor = false;
      }
      if (this.cur_emr) {
        this.update_emr();
      } else {
        this.get_allvisit("dok");
      }

      axios
        .post("/api/terlayani", {
          nomorantrean: this.cur_pasiendata.Antrian,
          token: this.token,
          kodepoli: this.cur_pasiendata.codeBPJS,
          tanggalperiksa: this.cur_pasiendata.TglKunjungan.substr(0, 10),

          kode_dok: this.cur_pasiendata.code_mjkn
        })
        .then(response => {})
        .catch(error => {
          if (error.response) {
            console.log(error.response.data); //Logs a string: Error: Request failed with status code 404
          }
        });
      let visit = this.cur_pasiendata;
      visit["type"] = status;
      visit["newdocid"] = newid;
      visit["newunit"] = newunit;
      axios.post("/api/update_visit", visit).then(response => {
        if (visit.type == "Konsul") {
          this.show_visit = true;
        }
      });
    },
    cancel() {
      var r = confirm("Apakah mau batal registrasi dan EMR?");
      let visit = this.cur_pasiendata;
      visit["type"] = "Batal";
      visit["name"] = this.$store.getters.currentuser.name;
      if (r) {
        axios.post("/api/update_visit", visit).then(response => {
          //  this.get_allvisit("dok");
          this.cur_emr ? this.update_emr("Batal") : "";
          this.$toastr.s("Registrasi sudah Batal", "Sukses!");
          // this.show_visit = true;
          // this.nama_search = "";
        });
      }
    },
    insert_visit_detail(tarif) {
      var visit_details = {
        NoRegistrasi: this.cur_pasiendata.NoRegistrasi,
        Dokter: this.cur_pasiendata.Doctor_1,
        "Product ID": tarif.ID,
        JenisProduct: tarif.CategoryProduct,
        NamaProduct: tarif["Product Name"],
        Quantity: this.tarif_rwj_qty,
        "Unit Price":
          this.cur_pasiendata.PatientType.toString() == "3"
            ? this.tarif_rwj_qty * tarif.TarifJamsostek
            : this.tarif_rwj_qty * tarif.TarifRS,
        JasaDokter:
          this.cur_pasiendata.PatientType.toString() == "3"
            ? 0
            : tarif.JasaDokter,
        Discount: 0,
        "Status ID": 1,
        CategoryProduc: "Tindakan"
      };
      axios.post("/api/insert_rwj_tindakan", visit_details).then(response => {
        this.reload_tindakan(this.cur_pasiendata.NoRegistrasi);
      });
    },
    delete_rwj_tindakan(id) {
      axios
        .post("/api/delete_rwj_tindakan", {
          id: id
        })
        .then(response => {
          this.reload_tindakan(this.cur_pasiendata.NoRegistrasi);
        });
    },
    get_icd10(reg) {
      axios
        .post("/api/get_diagnosa_given_reg", {
          no: reg
        })
        .then(response => {
          this.list_diag = response.data.list_diag;
        });
    },
    get_icd9(reg) {
      axios
        .post("/api/get_procedure_given_reg", {
          no: reg
        })
        .then(response => {
          this.list_procedure = response.data.list_procedure;
        });
    },
    insert_icd10(reg, diag) {
      axios
        .post("/api/insert_diagnosa_given_reg", {
          NoRegistrasi: reg,
          Code_Diag: diag.code2,
          Desc_Diag: diag.description,
          Primer: 0
        })
        .then(response => {
          this.get_icd10(reg);
        });
    },
    insert_icd9(reg, proc) {
      axios
        .post("/api/insert_procedure_given_reg", {
          NoRegistrasi: reg,
          Code_Proc: proc.Code2,
          Desc_Proc: proc.Description,
          Primer: 0
        })
        .then(response => {
          this.get_icd9(reg);
        });
    },
    delete_icd10(id, reg) {
      axios
        .post("/api/delete_diagnosa_given_reg", {
          id: id
        })
        .then(response => {
          this.get_icd10(reg);
          this.diag_search = "";
        });
    },
    delete_icd9(id, reg) {
      axios
        .post("/api/delete_procedure_given_reg", {
          id: id
        })
        .then(response => {
          this.get_icd9(reg);
          this.proc_search = "";
        });
    },
    update_alergi() {
      axios
        .post("/api/update_alergi", {
          nomor: this.cur_pasiendata.NoMR,
          Alergi: this.cur_pasiendata.Alergi
        })
        .then(response => {});
    },
    reload_tindakan(reg) {
      axios
        .post("/api/get_rwj_tindakan", {
          no: reg
        })
        .then(response => {
          this.list_rj_tindakan = response.data.list_visit_details;
        });
    },
    reload(reg, name) {
      this.nama_search = "";
      // this.$route.params.id=reg;
      //   if(reg!=this.$route.params.id){
      // this.internal_direct_extra(reg);
      //   }
      this.nama_search = name;
      // // document.getElementById('nama').Value=name;
      this.$store.dispatch("get_pasiendata_noreg", { noreg: reg });
      this.reload_tindakan(reg);
      // this.$store.dispatch("get_list_obat", { noreg: reg + "-01" });
      axios
        .post("/api/allresep_per_pasien", {
          noreg: reg
        })
        .then(response => {
          this.list_obat = [];
          response.data.list_resep_per_pasien.forEach(element => {
            axios
              .post("/api/list_obat", {
                noreg: element.NoResep
              })
              .then(response => {
                this.list_obat.push(response.data.eticket);
              });
          });
        });
      this.show_visit = false;
    },
    activate(index) {
      this.active = index;
    },

    insert_emr() {
      axios
        .post(
          "/api/insert_emr",
          {
            tgl: this.cur_pasiendata.TglKunjungan,
            no: this.cur_pasiendata.NoRegistrasi,
            mr: this.cur_pasiendata.NoMR,
            nama: this.cur_pasiendata.PatientName
          },
          {}
        )
        .then(response => {
          this.$store.dispatch("get_pasiendata_noreg", {
            noreg: this.cur_pasiendata.NoRegistrasi
          });
          //let r = confirm("apakah pasien memiliki riwayat Alergi ?");
          if (false) {
            // M.toast({ html: " apakah pasien memiliki riwayat Alergi ????" });
          }
        });
    },
    update_emr(st = 0) {
      if (st == "Batal") {
        this.cur_emr.type = "Batal";
      }
      axios
        .post("/api/update_emr", this.cur_emr)
        .then(response => {
          // this.$store.dispatch("get_pasiendata_noreg", {
          //   noreg: this.cur_pasiendata.NoRegistrasi
          // });
          this.get_allvisit("dok");
          st == "no" || "Batal"
            ? ""
            : this.$toastr.s("E-MR sudah simpan", "Sukses!");
          this.show_visit = true;
          this.nama_search = "";
          // this.cur_emr=null;
          // M.toast({ html: "Berhasil  !" });
        })
        .catch(error => {
          console.log(error); //Logs a string: Error: Request failed with status code 404
        });
      this.update_alergi();
    },
    internal_direct() {
      // this.$store.dispatch('get_pasiendata_noreg',{"noreg":path});
      this.$router.push("/rwj");
    },
    internal_direct_extra(path) {
      // this.$store.dispatch('get_pasiendata_noreg',{"noreg":path});
      this.$router.push("/rwj/emr/" + path);
    }
  }
};
</script>

<style>
.label_mo {
  font-size: 12px;
  font-weight: 100;
}
.wrap-collabsible {
  margin-bottom: 1.2rem 0;
}

input[type="checkbox"] {
  display: none;
}

.lbl-toggle {
  display: block;

  font-weight: bold;
  font-family: monospace;
  font-size: 1.2rem;
  text-transform: uppercase;
  text-align: center;

  padding: 0.3rem;

  color: white;
  background: gray;

  cursor: pointer;

  border-radius: 7px;
  transition: all 0.25s ease-out;
}

.lbl-toggle:hover {
  color: #7c5a0b;
}

.lbl-toggle::before {
  content: " ";
  display: inline-block;

  border-top: 5px solid transparent;
  border-bottom: 5px solid transparent;
  border-left: 5px solid currentColor;
  vertical-align: middle;
  margin-right: 0.7rem;
  transform: translateY(-2px);

  transition: transform 0.2s ease-out;
}

.toggle:checked + .lbl-toggle::before {
  transform: rotate(90deg) translateX(-3px);
}

.collapsible-content {
  max-height: 0px;
  overflow: hidden;
  transition: max-height 0.25s ease-in-out;
}

.toggle:checked + .lbl-toggle + .collapsible-content {
  max-height: 50vh;
  width: 100%;
  overflow-y: auto;
  overflow-x: auto;
}

.toggle:checked + .lbl-toggle {
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.collapsible-content .content-inner {
  background: transparent;
  border-bottom: 1px solid rgba(250, 224, 66, 0.45);
  border-bottom-left-radius: 7px;
  border-bottom-right-radius: 7px;
  padding: 0.5rem 1rem;
}
.modal_soap-mask {
  position: fixed;
  z-index: 9995;
  top: 0;
  left: 0;
  width: 100%;
  max-height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
 

}

.modal_soap-wrapper {
  display: table-cell;
  vertical-align: middle;
  height: 100%;
}

.modal_soap-container {
  width: 100%;
 

  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal_soap-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal_soap-body {
  max-height: 700px;
  overflow-y: auto;
}
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 330px;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
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