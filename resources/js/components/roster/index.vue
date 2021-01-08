<template>
<div v-if="roles && user">
    <div v-if="show_shifts" >
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="chip right" @click="show_shifts = false">X</div>
                    <div class="modal-header">
            <slot name="header">Shifts</slot>
          </div>
                    <div v-if="shifts" class="modal-body">
                        <div name="body" style="max-height:450px;overflow-y:auto">
                            <table v-if="shifts" class="striped highlight"  >
                                <thead class="fixed">
                                    <tr>
                                        <th>Nama Shift</th>
                                        <th>Code Shift</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Keluar</th>
                                        <th @click="add_shift()"> <i class="btn fa fa-plus-circle">Tambah</i> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(shift, ind) in shifts" :key="ind">
                                        <td>
                                            <input type="text" v-model="shift.nama" />
                                        </td>
                                        <td>
                                            <input type="text" v-model="shift.code" />
                                        </td>
                                        <td>
                                            <input type="time" v-model="shift.jam_masuk">
                                        </td>
                                        <td>
                                            <input type="time" v-model="shift.jam_keluar" />
                                        </td>
                                        <td v-if="shift.type!='*'">
                                            <span class="btn rounded" @click="update_shifts(shift)"><i class="fa fa-save"></i></span>
                                            <span class="btn rounded-full" @click="delete_shift(shift.id)"><i class="fa fa-trash"></i></span>
                                            <span class="btn rounded-full">
                                                <i v-if="shift.mix=='false'" @click="shift.mix='true'" class="fa fa-circle-notch"></i>
                                                <i v-else class="fa fa-check-circle" @click="shift.mix='false'"></i>
                                                <i class="fab fa-mixer"></i></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row col s12">
        <div class="col s1">
            <input type="number" @change="get_roster" v-model="cur_month" name max="12" min="1" id />
        </div>
        <div class="col s1">
            <input type="number" v-model="cur_year" min="2020" maxlength="4" />
        </div>
        <div class="col s1">
            <div class="dd_head">
                <button v-if="show_adminmod" @click="show_shifts = true" class="btn success">shift</button>
                <ul class="dd_ul" v-if="show_kryawan">
                    <li class="dd_li animate__animated animate__fadeInUp" v-for="(emp, ind) in cur_employees" :key="ind">
                        <a>{{ emp.name }}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="s2 col" v-if="role_all.length>0&&show_adminmod">
            <select class="browser-default" v-model="cur_dep" @change="get_all()" >
              <option v-for="(role,ind) in role_all_list" :key="ind"  :value="role.role_code">{{role.role_name}}</option>
            </select>
        </div>
        <div v-if="show_adminmod" class="s2 col">
            <button class="btn warning" @click="get_roster()">Cari Roster</button>
        </div>
        
        <div class="s1 col">

        <button v-if="show_adminmod" class="btn warning" @click="insert_allemp()">Process</button>
        </div>
        <div v-if="show_adminmod" class="s4 col">
            <form id="uploadForm" role="form" enctype=multipart/form-data class="row" >
                <div>
                  <input type="file" id="file" name="file" /><input @click="upload_csv" value=Upload />
                  </div>
            </form>
        </div>
    </div>
    <div v-if="show_adminmod">
<button class="btn success" @click="show_chart=false">Table</button>
    <button class="btn btn-danger" @click="show_chart=true">Chart</button>
    <button class="btn" @click="show_note=!show_note">{{show_note?'Edit':'Result'}}</button>
    <button class="btn btn-warning" @click="onexport">download</button>

    </div>
    
    
    <!-- {{cur_employees_filter}} -->
    <div id="chart" v-if="show_chart">
   <apexchart type="rangeBar" height="350" :options="chartOptions" :series="series"></apexchart> 
        
        
      
    </div>
    <!-- <div id="chart1" v-if="show_chart">
    <apexchart type="candlestick" height="350" :options="chartOptions1" :series="series"></apexchart> 
    </div> -->
    <div style="margin-left: 10px; margin-right: 10px" v-if="cur_employees_filter != null && !show_chart">
        <table v-if="cur_employees_filter[0].data" class="striped highlight" style="max-height: 350px; overflow-y: auto">
            <thead class="fixed">
                <tr>
                    <th>Nama Petugas</th>
                    <th v-for="(num, i) in cur_employees_filter[0].data.length" :key="i">
                        {{ i + 1 }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(emp, inde) in cur_employees_filter" :key="inde">
                    <td>{{ emp.name }}</td>
                    <td v-for="(day, index) in emp.data" :key="index"  @mouseover="show_note?show_card(emp,day):''" @mouseleave="show_note?hide_card(emp,day):''" >
                        <div class="cell ">
                            <div class="dd_head">
                            <div v-if="day.type_shift!='*'&&day.show&&show_note&&show_tempnote"   class="dd_ul animate__animated animate__fadeIn animate__fast" :style="'width:200px;position:absolute;padding:10px;margin-right:-170px;margin-top:-'+'150'+'px'">
                    <div class="right" @click="show_tempnote=false">X</div>
                               <li class="badge white">
                                    {{day.tanggal| formatDate}}
                                  </li>
                                <div v-for="(item,ind) in day.evaluateddata" :key="ind">
                                <!-- <div v-if="item.cin=='' && item.cout==''">
                                no FInger data </div> -->
                                <div >
                                <ul>
                                  <li >
                                   <span class="badge blue white-text"> kerja - {{item.jam_kerja}}</span>
                                  </li>
                                <li>
                              <i class="fa fa-walking green-text ">{{item.rin!=''?item.rin:'Tidak Ada'}} </i>
                              <i class="fa fa-fingerprint green-text ">{{item.cin!=''?item.cin:'Tidak Ada'}} ( {{item.cin!=''?item.cin_diff :''}} )</i>
                                </li>
                                <li>
                                <i class="fa fa-walking pink-text">{{item.rout!=''?item.rout:'Tidak Ada'}} </i>
                                <i class="fa fa-fingerprint pink-text">{{item.cout!=''?item.cout:'Tidak Ada'}} ( {{item.cout!=''?item.cout_diff:''}} )</i>
                                </li>
                                
                                </ul>
                                </div>
                                </div>
                                </div>
                                <span @click="change_show(inde, index, day, !day.show)">
                                    <!-- <span class="fa-layers fa-fw" style="background:MistyRose">
    <i class="fas fa-calendar"></i>
    <span class="fa-layers-text fa-inverse" data-fa-transform="shrink-8 down-3" style="font-weight:900">27</span>
  </span> -->
                                    <span v-if="!day.shift" class="white" style="padding:3px">
                                        {{ index + 1 }}
                                    </span>
                                    <i v-else style="font-size: 12px" :class="'rounded_shift fa fa-' + get_icon(day.shift) + ''">
                                        {{ day.shift }}
                                    </i>
                                </span>
                                
                                <ul class="dd_ul" v-if="day.show&&!show_note">
                                    <li v-for="(shift, ind) in shifts" :key="ind" class="">
                                        <i @click="deselect_shift(inde,index,day,shift)" v-if="day.shift?is_checked(shift.code,day.shift):false" class="fa fa-check-circle green-text"></i>
                                        <i @click="select_multiple_shifts(inde,index,day,shift)" v-else class="fa fa-circle-notch green-text"></i>
                                        <i :class="'fa fa-' + get_icon(shift.nama) + ''"></i>
                                        {{ shift.code }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</template>
<script>
import XLSX from "xlsx";
import Axios from "axios";
import dayjs from "dayjs";
export default {
  data() {
    var d = new Date();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();
    return {
      show_adminmod:false,
      Datas: {},
      role_all: [],
      cur_dep: "Kantor1",
      show_tempnote: true,
      show_note: true,
      cur_evaluateddata: [],
      preday_roster: [],
      json_datetime: [],
      show_chart: false,
      chartOptions: {
        chart: {
          height: 850,
          type: "rangeBar",
        },
        plotOptions: {
          bar: {
            horizontal: true,
            barHeight: "100%",
            rangeBarGroupRows: true,
          },
        },
        stroke: {
          width: 1,
        },
        fill: {
          type: "solid",
          opacity: 0.6,
        },
        legend: {
          position: "top",
          horizontalAlign: "left",
        },
      },
      chartOptions1: {
        chart: {
          height: 350,
          type: "candlestick",
        },
        title: {
          text: "CandleStick Chart - Category X-axis",
          align: "left",
        },
        annotations: {
          xaxis: [
            {
              x: "Oct 06 14:00",
              borderColor: "#00E396",
              label: {
                borderColor: "#00E396",
                style: {
                  fontSize: "12px",
                  color: "#fff",
                  background: "#00E396",
                },
                orientation: "horizontal",
                offsetY: 7,
                text: "Annotation Test",
              },
            },
          ],
        },
        tooltip: {
          enabled: true,
        },
        xaxis: {
          type: "category",
          labels: {
            formatter: function (val) {
              return dayjs(val).format("MMM DD HH:mm");
            },
          },
        },
        yaxis: {
          tooltip: {
            enabled: true,
          },
        },
      },
      show_shifts: false,
      selected_shifts: [],
      show_kryawan: false,
      shifts: [],
      cur_roster: [],
      cur_employees: [],
      cur_month: month,
      cur_year: year,
      cur_roser_filtered: [],
    };
  },
  mounted() {
    this.get_roles();
    // this.get_employees();
  },
  methods: {
    onexport() {
      // On Click Excel download button

      // export json to Worksheet of Excel
      // only array possible

      // //console.log(this.Datas);
      //       for(let i=0;i<this..length;i++) {
      this.cur_employees.forEach((element1) => {
        let element = this.Datas[element1.name];
        // //console.log(element);

        let telat_hr_in = 0;
        let telat_in_count = 0;

        let telat_hr_out = 0;
        let telat_out_count = 0;

        let kerja_in = 0;
        let kerja_count = 0;
        element.forEach((ele) => {
          if (ele.cin_diff) {
            telat_in_count++;
          }
          if (ele.cout_diff) {
            telat_out_count++;
          }
          //console.log(ele.cin_diff.split('')[0]);
          if (ele.cin_diff && ele.cin_diff.split("")[0] == "-") {
            let telat_in = ele.cin_diff.substring(1).split(":");
            // console.log(telat_in);
            telat_hr_in +=
              1000 *
              (parseInt(telat_in[0]) * 60 * 60 +
                parseInt(telat_in[1]) * 60 +
                parseInt(telat_in[2]));
            // console.log(telat_hr_in+'updateing ...')
          }
          if (ele.cout_diff && ele.cout_diff.split("")[0] == "-") {
            let telat_out = ele.cout_diff.substring(1).split(":");
            //console.log(telat_out);

            telat_hr_out +=
              1000 *
              (parseInt(telat_out[0]) * 60 * 60 +
                parseInt(telat_out[1]) * 60 +
                parseInt(telat_out[2]));
          }
          if (ele.jam_kerja) {
            let kerja = ele.jam_kerja.split(":");
            kerja_in +=
              1000 *
              (parseInt(kerja[0]) * 60 * 60 +
                parseInt(kerja[1]) * 60 +
                parseInt(kerja[2]));
            kerja_count++;
            //           let kin=ele.cin.split(':')
            //           let cin= (1000*(parseInt(kin[0])*60*60)+(parseInt(kin[1])*60));
            // let kout=ele.cout.split(':')
            //           let cout= (1000*(parseInt(kout[0])*60*60)+(parseInt(kout[1])*60));
            //           kerja_in += (cout-cin)
          }
        });
        let temp = {
          tgl: " Rata2 ",
          dinas: "-",
          rin: "-",
          rout: "-",
          cin: "-",
          cout: "-",
          cin_diff: "-",
          cout_diff: "-",
        };
        // this.Datas[i].push(temp);
        // //console.log(element);
        console.log(kerja_count + " is the count");
        console.log(telat_hr_in + " in milli");
        console.log(telat_hr_out + " out milli");
        console.log(kerja_in + " kerja milli");
        console.log(kerja_count + " is the count");
        temp.cin_diff = this.timedetails(telat_hr_in / telat_in_count);
        temp.cout_diff = this.timedetails(telat_hr_out / telat_out_count);
        temp.jam_kerja = this.timedetails(kerja_in / kerja_count);
        element.push(temp);
      });
      var wb = XLSX.utils.book_new();
      this.cur_employees.forEach((element) => {
        var temp = XLSX.utils.json_to_sheet(this.Datas[element.name]);
        XLSX.utils.book_append_sheet(wb, temp, element.name);
      });
      // var pokemonWS = XLSX.utils.json_to_sheet(this.Datas.pokemons)

      // A workbook is the name given to an Excel file
      // make Workbook of Excel

      // add Worksheet to Workbook
      // Workbook contains one or more worksheets
      // XLSX.utils.book_append_sheet(wb, animalWS, 'animals') // sheetAName is name of Worksheet

      // export Excel file
      XLSX.writeFile(
        wb,
        this.cur_dep +
          "_" +
          "bln " +
          this.cur_month +
          "_tahun_" +
          this.cur_year +
          ".xlsx"
      ); // name of the file is 'book.xlsx'
    },
    show_card(emp, day) {
      // //console.log('entered');
      this.show_tempnote = true;
      day.show = true;
      this.cur_employees.forEach((element) => {
        if (true) {
          element.data.forEach((element1) => {
            if (element1.id == day.id) {
              element1.show = true;
            } else {
              element1.show = false;
            }
          });
        }
      });
      this.update_dom();
    },
    hide_card(emp, day) {
      // //console.log('left');

      // this.show_tempnote = false;
      day.show = false;
    },
    get_all() {
      this.get_employees(this.cur_dep);
      this.get_roster();
      this.get_allshifts();
    },
    get_roles() {
      axios.post("/api/get_roles", {}).then((response) => {
        this.role_all = response.data.data;
      });
    },
    timedetails(millisec) {
      var seconds = (millisec / 1000).toFixed(0);
      var minutes = Math.floor(seconds / 60);
      var hours = "";
      if (minutes > 59) {
        hours = Math.floor(minutes / 60);
        hours = hours >= 10 ? hours : "0" + hours;
        minutes = minutes - hours * 60;
        minutes = minutes >= 10 ? minutes : "0" + minutes;
      }
      seconds = Math.floor(seconds % 60);
      seconds = seconds >= 10 ? seconds : "0" + seconds;
      if (hours != "") {
        return hours + ":" + minutes + ":" + seconds;
      }
      return "00:" + minutes + ":" + seconds;
    },
    evaluate_jsondatetime(ob) {
      this.cur_evaluateddata = [];
      let temp = JSON.parse(ob);
      if (temp) {
        temp.forEach((element, ind) => {
          let millisec = element.c_in - element.masuk_timestamp;
          let temp_difcin = element.masuk_timestamp - element.c_in;
          let temp_difcout = element.c_out - element.keluar_timestamp;
          let diff_cin =
            (temp_difcin < 0 ? "-" : "") +
            this.timedetails(temp_difcin < 0 ? -1 * temp_difcin : temp_difcin);
          let diff_cout =
            (temp_difcout < 0 ? "-" : "") +
            this.timedetails(
              temp_difcout < 0 ? -1 * temp_difcout : temp_difcout
            );
          //             let unique_shifts = element.shift_code.filter(function(item, pos) {
          //     return a.indexOf(item) == pos;
          // });
          var uniqueNames = [];
          element.shift_code.split(",").forEach((el) => {
            if (uniqueNames.includes(el) === -1) uniqueNames.push(el);
          });

          let temp_ele = {
            tgl: "",
            dinas: uniqueNames.toString(),
            rin: element.masuk,
            rout: element.keluar,
            cin: element.c_in
              ? new Date(element.c_in).getHours() +
                ":" +
                new Date(element.c_in).getMinutes()
              : "",
            cout: element.c_out
              ? new Date(element.c_out).getHours() +
                ":" +
                new Date(element.c_out).getMinutes()
              : "",
            //   jin: element.masuk_timestamp,
            //   cout: new Date(element.c_out),
            //   jout: element.keluar_timestamp,
            cin_diff: element.c_in ? diff_cin : "",
            cout_diff: element.c_out ? diff_cout : "",
            jam_kerja:
              element.c_in && element.c_out
                ? this.timedetails(element.c_out - element.c_in)
                : "",
          };
          this.cur_evaluateddata.push(temp_ele);
        });
      }
    },
    is_checked(check, list) {
      let lists = list.split(",");
      let ret = false;
      lists.forEach((element) => {
        if (element.toUpperCase() == check.toUpperCase()) {
          ret = true;
        }
      });
      return ret;
    },
    add_shift() {
      axios
        .post("/api/insert_shift", {
          type: this.cur_dep,
          user_id: this.user.emp_id,
        })
        .then((response) => {
          this.get_allshifts();
        });
    },
    delete_shift(id) {
      axios
        .post("/api/delete_shift", {
          id: id,
        })
        .then((response) => {
          this.get_allshifts();
        });
    },
    update_shifts(shift) {
      axios.post("/api/update_shift", shift).then((response) => {
        this.get_allshifts();
      });
    },
    insert_allemp() {
      this.cur_employees.forEach((element) => {
        this.insert_roster(element);
      });
    },
    select_multiple_shifts(inde, index, day, shift) {
      ////console.log(shift);
      let ret = 1;
      let pre_monthday = {};
      let preday = {};
      this.preday_roster.forEach((element) => {
        if (element.emp_id == day.emp_id) {
          pre_monthday = element;
        }
      });
      if (index == 0) {
        preday = pre_monthday;
      } else {
        preday = this.cur_employees[inde].data[index > 0 ? index - 1 : 0];
      }
      let nextday = this.cur_employees[inde].data[index > 0 ? index + 1 : 0];
      if (
        shift.mix == "true" &&
        index != this.cur_employees[inde].data.length - 1
      ) {
        this.nullify_day(nextday);
      }
      if (preday.shift == null && index != 0) {
        this.$toastr.e(" Silahkan isi jadwal pada hari sebulumnya  ");
        return true;
      }
      //console.log("previous day" + JSON.stringify(preday));
      if (
        index != 0 &&
        preday.mix == 1 &&
        preday.jam_keluar != null &&
        shift.type != "*"
      ) {
        if (preday.jam_keluar.split(" ")[1].substring(0, 8) > shift.jam_masuk) {
          this.$toastr.e("Shift Conflict !");
          ret = 0;
        }
      }
      if (this.selected_shifts.length > 0) {
        // let selected_hours=shift.split(':')[0];
        if (shift.type == "*") {
          var conf = confirm(
            "Gak bisa pilih shift ini sama shift lain - `ok` untk pilih kusus ini `cancel` untk batal"
          );
          if (conf) {
            this.selected_shifts = [];
          } else {
            ret = 0;
          }
        }
        this.selected_shifts.forEach((element) => {
          if (element.type == "*") {
            var conf = confirm(
              "Gak bisa pilih shift ini sama shift lain - `ok` untk pilih kusus ini `cancel` untk batal"
            );
            if (conf) {
              this.selected_shifts = [];
            } else {
              ret = 0;
            }
          }
          //console.log(preday);
          if (
            (shift.jam_masuk < element.jam_keluar &&
              shift.jam_masuk > element.jam_masuk) ||
            (shift.jam_keluar > element.jam_masuk &&
              shift.jam_keluar < element.jam_keluar &&
              shift.mix != "true")
          ) {
            this.$toastr.e(
              "Jam Masuk di Shift Ini ada konflik dengan shift lain .. silahkan periksa jam masuk dan jam kelusr shift tsb"
            );
            ret = 0;
          }
        });
      }
      if (ret == 1) {
        this.selected_shifts.push(shift);
        this.update_shift(day, shift, preday);
      }
    },
    deselect_shift(inde, index, day, shift) {
      let pre_monthday = {};
      let preday = {};
      this.preday_roster.forEach((element) => {
        if (element.emp_id == day.emp_id) {
          pre_monthday = element;
        }
      });
      if (index == 0) {
        preday = pre_monthday;
      } else {
        preday = this.cur_employees[inde].data[index > 0 ? index - 1 : 0];
      }
      let nextday = this.cur_employees[inde].data[index > 0 ? index + 1 : 0];
      if (
        shift.mix == "true" &&
        index != this.cur_employees[inde].data.length - 1
      ) {
        this.nullify_day(nextday);
      }
      if (this.selected_shifts.length == 1) {
        confirm("shift gak boleh kosong !");
        return;
      }
      let temp = [];
      this.selected_shifts.forEach((element, ind) => {
        if (element.code == shift.code) {
          // this.selected_shifts.remove(ind);
        } else {
          temp.push(element);
        }
      });
      this.selected_shifts = temp;
      this.update_shift(day, shift, preday);
    },
    nullify_day(day) {
      day.shift = null;
      day.mix = 0;
      day.jam_keluar1 = null;
      day.jam_keluar1 = null;
      day.json_datetime = null;
      axios.post("/api/update_roster", day).then((response) => {});
    },
    update_shift(day, shift, previous_day = 0) {
      ////console.log(shift);
      ////console.log(day);
      this.json_datetime = [];
      if (true) {
        let codes = [];
        day["mix"] = 0;
        this.selected_shifts.forEach((element) => {
          codes.push(element.code);
          var d1 = new Date(Date.parse(day.tanggal));
          let out = element.jam_keluar.split(":");
          let inn = element.jam_masuk.split(":");
          if (element.mix == "true") {
            day["mix"] = 1;
            d1.setDate(d1.getDate() + 1);
          }
          d1.setHours(out[0]);
          d1.setMinutes(out[1]);
          d1.setSeconds(0);
          var d2 = new Date(Date.parse(day.tanggal));
          d2.setHours(inn[0]);
          d2.setMinutes(inn[1]);
          d2.setSeconds(0);
          element["timestamp_in"] = d2.getTime();
          element["timestamp_out"] = d1.getTime();
          let temp_json_datetime = {
            shift_code: element.code,
            masuk: element.jam_masuk,
            keluar: element.jam_keluar,
            masuk_timestamp: element.timestamp_in,
            keluar_timestamp: element.timestamp_out,
            c_in: "",
            c_out: "",
            functional: "1",
          };
          this.json_datetime.push(temp_json_datetime);
          ////console.log(element);
          ////console.log(d1);
          ////console.log("date 2 is " + d2);
        });
        let jam_masuk = this.selected_shifts[0];
        let jam_keluar = this.selected_shifts[this.selected_shifts.length - 1];
        this.selected_shifts.forEach((element) => {
          if (element.timestamp_in < jam_masuk.timestamp_in) {
            jam_masuk = element;
          }
          if (element.timestamp_out > jam_keluar.timestamp_out) {
            jam_keluar = element;
          }
        });
        day.shift = codes.toString();
        day["jam_keluar1"] = jam_keluar.timestamp_out;
        day.type_shift = shift.type;
        day["jam_masuk1"] = jam_masuk.timestamp_in;
      } else if (this.selected_shifts.length == 1) {
        shift = this.selected_shifts[0];
        day.shift = shift.code;
        day["jam_keluar1"] = shift.timestamp_out;
        day.type_shift = shift.type;
        day["jam_masuk1"] = shift.timestamp_in;
      }
      //console.log("before preaparing json");
      let final_json_datetime = this.prepare_jsondatetime(previous_day);
      //console.log(this.json_datetime);
      day["json_datetime"] = JSON.stringify(this.json_datetime);
      this.update_dom();
      axios.post("/api/update_roster", day).then((response) => {
        // this.get_roster();
      });
    },
    prepare_jsondatetime(previous_day = 0) {
      let remove_list = [];
      let temp_list = [];
      let finish = 0;
      //console.log("shifts are");
      //console.log(this.selected_shifts);
      //console.log("json datetime are");
      //console.log(this.json_datetime);
      this.json_datetime.forEach((element, ind) => {
        this.json_datetime.forEach((element1, index) => {
          if (element.keluar_timestamp == element1.masuk_timestamp) {
            remove_list.push(index);
            remove_list.push(ind);
            if (element.functional != 0) {
              let temp = {
                shift_code: element.shift_code + "," + element1.shift_code,
                masuk: element.masuk,
                keluar: element1.keluar,
                masuk_timestamp: element.masuk_timestamp,
                keluar_timestamp: element1.keluar_timestamp,
                c_in: "",
                c_out: "",
              };
              temp_list.push(temp);
            }
            let final_json_datetime = [];
            this.json_datetime.forEach((element, index) => {
              if (!remove_list.includes(index)) {
                final_json_datetime.push(element);
              }
            });
            temp_list.forEach((element) => {
              final_json_datetime.push(element);
            });
            if (ind == this.json_datetime.length - 1) {
              //console.log("index is " + ind);
              //console.log("json length " + this.json_datetime.length);
              this.json_datetime = final_json_datetime;
              return this.json_datetime;
            } else {
              //console.log("still ..");
              this.json_datetime = final_json_datetime;
              this.prepare_jsondatetime(previous_day);
            }
          }
        });
      });
      //console.log("previous day is ");
      //console.log(previous_day.tanggal);
      let json_datetime = null;
      if (previous_day.tanggal) {
        json_datetime = JSON.parse(previous_day.json_datetime);
      }
      if (json_datetime) {
        this.json_datetime.forEach((element) => {
          //console.log("elements masuk timestamp" + element.masuk_timestamp);
          //console.log("previous days klr timestamp" + previous_day.jam_keluar1);
          if (element.masuk_timestamp == previous_day.jam_keluar1) {
            // remove_list.push(ind);
            element["functional"] = 0;
            //console.log("entered into matching case");
            json_datetime[json_datetime.length - 1].keluar = element.keluar;
            json_datetime[json_datetime.length - 1].keluar_timestamp =
              element.keluar_timestamp;
            previous_day["json_datetime"] = JSON.stringify(json_datetime);
            axios
              .post("/api/update_roster", previous_day)
              .then((response) => {});
          }
        });
      }
    },
    change_show(first, second, day, state) {
      this.selected_shifts = [];
      if (day.shift) {
        let s_list = day.shift.split(",");
        this.shifts.forEach((element) => {
          s_list.forEach((ele) => {
            if (element.code == ele) {
              this.selected_shifts.push(element);
            }
          });
        });
      }
      // this.cur_employees[first].data[second].show=!this.cur_employees[first].data[second].show;
      this.cur_employees.forEach((element) => {
        element.data.forEach((element1) => {
          if (element1.show == true) {
            element1.show = false;
          }
        });
      });
      day.show = state;
      if (this.show_note) {
        this.show_tempnote = true;
        this.evaluate_jsondatetime(day.json_datetime);
      } else {
      }
      this.update_dom();
    },
    update_dom() {
      let temp = this.cur_employees_filter;
      this.cur_employees = {};
      this.cur_employees = temp;
    },
    insert_roster(emp) {
      this.show_kryawan = false;
      let data = {
        emp_id: emp.emp_id,
        bulan: this.cur_month,
        tahun: this.cur_year,
        dep: emp.dep,
        roster_dep:emp.roster_dep,
      };
      axios
        .post("/api/insert_roster", data)
        .then((response) => {
          this.get_roster();
        })
        .catch((er) => {
          ////console.log(er);
        });
    },
    get_employee_withid(id) {
      let name = "";
      this.cur_employees.forEach((element) => {
        if (element.emp_id == id) {
          ////console.log(element);
          name = element.name;
        }
      });
      return name;
    },
    get_employees(role) {
      axios
        .post("/api/get_employees", {
          role: role,
        })
        .then((response) => {
          this.cur_employees = response.data.data;
          this.get_roster();
          this.get_allshifts();
        });
    },
    get_icon(shift) {
      // ////console.log(shift.charAt(0).toUpperCase()+'shift is ');
      let ss = shift.charAt(0).toUpperCase();
      if (ss == "P") {
        return "coffee blue-text";
      } else if (ss == "S") {
        return "cloud-sun green-text";
      } else if (ss == "M") {
        return "moon pink-text";
      } else if (ss == "C") {
        return "envelope red-text";
      } else if (ss == "L") {
        return "shopping-bag red-text";
      }
      else{
        return "envelope red-text";
      }
    },
    get_allshifts() {
      axios
        .post("/api/get_allshift", {
          type: this.cur_dep,
        })
        .then((response) => {
          this.shifts = response.data.data;
          this.shifts.forEach((element) => {
            element.jam_masuk = element.jam_masuk.substring(0, 8);
            element.jam_keluar = element.jam_keluar.substring(0, 8);
          });
        });
    },
    get_roster(bl_th = 0) {
      let data = {
        bulan: this.cur_month,
        tahun: this.cur_year,
        dep: this.cur_dep,
      };
      // if (!bl_th) {
      //   var d = new Date();
      //   var month = d.getMonth() + 1;
      //   var year = d.getFullYear();
      //   data.bulan = ("0" + month).slice(-2);
      //   data.tahun = ("0" + year).slice(-4);
      //   // ////console.log(data);
      // } else {
      //   data = bl_th;
      // }
      this.Datas = {};
      axios.post("/api/get_roster", data).then((response) => {
        this.cur_roster = response.data.data;
        this.preday_roster = response.data.preday_data;

        this.cur_employees.forEach((element) => {
          element["data"] = [];
          this.Datas[element.name] = [];
        });
        let jsondata = [];

        if (true) {
          // this.Datas=[];

          ////console.log(this.cur_employees);
          this.cur_roster.forEach((element) => {
            this.evaluate_jsondatetime(element.json_datetime);
            element["evaluateddata"] = this.cur_evaluateddata;
            element.evaluateddata.forEach((element2) => {
              element2.tgl = element.tanggal;
            });
            // element["evaluateddata"]["tgl"]=element.tanggal;
            // //console.log(element.tanggal);
            element["show"] = false;
            this.cur_employees.forEach((emps, ind) => {
              if (emps.emp_id == element.emp_id) {
                // //console.log(this.Datas[emps.name]);
                if (!this.Datas[emps.name]) {
                  this.Datas[emps.name] = [];
                }
                element.evaluateddata.forEach((ele) => {
                  this.Datas[emps.name].push(ele);
                });

                emps.data.push(element);
              }
            });
          });
        }
        // this.cur_roser_filtered= jsondata;
      });
    },
    upload_csv() {
      var formData = new FormData();
      var csv = document.querySelector("#file");
      formData.append("csv", csv.files[0]);
      axios
        .post("/api/upload_csv", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          this.get_roster();
        });
    },
  },
  computed: {
    role_all_list() {
      return this.role_all;
    },
    series() {
      let series1 = [];
      let temp_data = [];
      if (this.cur_employees && this.cur_roster.length > 0) {
        this.cur_employees.forEach((element) => {
          let temp_name = element.name;
          temp_data = [];
          element.data.forEach((element1) => {
            if (element1.type_shift != "*" || true) {
              let obj = JSON.parse(element1.json_datetime);
              if (obj != null) {
                obj.forEach((ele) => {
                  let temp_obj = {
                    x: element1.tanggal,
                    y: [
                      parseFloat(
                        new Date(ele.masuk_timestamp).getHours() +
                          "." +
                          new Date(ele.masuk_timestamp).getMinutes()
                      ),
                      parseFloat(
                        new Date(ele.keluar_timestamp).getHours() +
                          "." +
                          new Date(ele.keluar_timestamp).getMinutes()
                      ),
                      // parseFloat(
                      //   new Date(ele.c_in?ele.c_in:0).getHours() +
                      //     "." +
                      //     new Date(ele.c_in?ele.c_in:0).getMinutes()
                      // ),
                      // parseFloat(
                      //   new Date(ele.c_out?ele.c_out:0).getHours() +
                      //     "." +
                      //     new Date(ele.c_out?ele.c_out:0).getMinutes()
                      // ),
                      // new Date(ele.keluar_timestamp).getTime(),
                      // parseFloat(ele.keluar.substring(0, 2) + '.' + ele.keluar.substring(3, 2)),
                    ],
                  };
                  temp_data.push(temp_obj);
                });
              }
            }
          });
          let temp = {
            name: temp_name,
            data: temp_data,
          };
          series1.push(temp);
        });
      }
      return series1;
    },
    series1() {
      let series_t = [
        {
          name: "candle",
          data: [
            {
              x: new Date(1538778600000),
              y: [6629.81, 6650.5, 6623.04, 6633.33],
            },
            {
              x: new Date(1538780400000),
              y: [6632.01, 6643.59, 6620, 6630.11],
            },
            {
              x: new Date(1538782200000),
              y: [6630.71, 6648.95, 6623.34, 6635.65],
            },
            {
              x: new Date(1538784000000),
              y: [6635.65, 6651, 6629.67, 6638.24],
            },
            {
              x: new Date(1538785800000),
              y: [6638.24, 6640, 6620, 6624.47],
            },
            {
              x: new Date(1538787600000),
              y: [6624.53, 6636.03, 6621.68, 6624.31],
            },
            {
              x: new Date(1538789400000),
              y: [6624.61, 6632.2, 6617, 6626.02],
            },
            {
              x: new Date(1538791200000),
              y: [6627, 6627.62, 6584.22, 6603.02],
            },
            {
              x: new Date(1538793000000),
              y: [6605, 6608.03, 6598.95, 6604.01],
            },
            {
              x: new Date(1538794800000),
              y: [6604.5, 6614.4, 6602.26, 6608.02],
            },
            {
              x: new Date(1538796600000),
              y: [6608.02, 6610.68, 6601.99, 6608.91],
            },
            {
              x: new Date(1538798400000),
              y: [6608.91, 6618.99, 6608.01, 6612],
            },
            {
              x: new Date(1538800200000),
              y: [6612, 6615.13, 6605.09, 6612],
            },
            {
              x: new Date(1538802000000),
              y: [6612, 6624.12, 6608.43, 6622.95],
            },
            {
              x: new Date(1538803800000),
              y: [6623.91, 6623.91, 6615, 6615.67],
            },
            {
              x: new Date(1538805600000),
              y: [6618.69, 6618.74, 6610, 6610.4],
            },
            {
              x: new Date(1538807400000),
              y: [6611, 6622.78, 6610.4, 6614.9],
            },
            {
              x: new Date(1538809200000),
              y: [6614.9, 6626.2, 6613.33, 6623.45],
            },
            {
              x: new Date(1538811000000),
              y: [6623.48, 6627, 6618.38, 6620.35],
            },
            {
              x: new Date(1538812800000),
              y: [6619.43, 6620.35, 6610.05, 6615.53],
            },
            {
              x: new Date(1538814600000),
              y: [6615.53, 6617.93, 6610, 6615.19],
            },
            {
              x: new Date(1538816400000),
              y: [6615.19, 6621.6, 6608.2, 6620],
            },
            {
              x: new Date(1538818200000),
              y: [6619.54, 6625.17, 6614.15, 6620],
            },
            {
              x: new Date(1538820000000),
              y: [6620.33, 6634.15, 6617.24, 6624.61],
            },
            {
              x: new Date(1538821800000),
              y: [6625.95, 6626, 6611.66, 6617.58],
            },
            {
              x: new Date(1538823600000),
              y: [6619, 6625.97, 6595.27, 6598.86],
            },
            {
              x: new Date(1538825400000),
              y: [6598.86, 6598.88, 6570, 6587.16],
            },
            {
              x: new Date(1538827200000),
              y: [6588.86, 6600, 6580, 6593.4],
            },
            {
              x: new Date(1538829000000),
              y: [6593.99, 6598.89, 6585, 6587.81],
            },
            {
              x: new Date(1538830800000),
              y: [6587.81, 6592.73, 6567.14, 6578],
            },
            {
              x: new Date(1538832600000),
              y: [6578.35, 6581.72, 6567.39, 6579],
            },
            {
              x: new Date(1538834400000),
              y: [6579.38, 6580.92, 6566.77, 6575.96],
            },
            {
              x: new Date(1538836200000),
              y: [6575.96, 6589, 6571.77, 6588.92],
            },
            {
              x: new Date(1538838000000),
              y: [6588.92, 6594, 6577.55, 6589.22],
            },
            {
              x: new Date(1538839800000),
              y: [6589.3, 6598.89, 6589.1, 6596.08],
            },
            {
              x: new Date(1538841600000),
              y: [6597.5, 6600, 6588.39, 6596.25],
            },
            {
              x: new Date(1538843400000),
              y: [6598.03, 6600, 6588.73, 6595.97],
            },
            {
              x: new Date(1538845200000),
              y: [6595.97, 6602.01, 6588.17, 6602],
            },
            {
              x: new Date(1538847000000),
              y: [6602, 6607, 6596.51, 6599.95],
            },
            {
              x: new Date(1538848800000),
              y: [6600.63, 6601.21, 6590.39, 6591.02],
            },
            {
              x: new Date(1538850600000),
              y: [6591.02, 6603.08, 6591, 6591],
            },
            {
              x: new Date(1538852400000),
              y: [6591, 6601.32, 6585, 6592],
            },
            {
              x: new Date(1538854200000),
              y: [6593.13, 6596.01, 6590, 6593.34],
            },
            {
              x: new Date(1538856000000),
              y: [6593.34, 6604.76, 6582.63, 6593.86],
            },
            {
              x: new Date(1538857800000),
              y: [6593.86, 6604.28, 6586.57, 6600.01],
            },
            {
              x: new Date(1538859600000),
              y: [6601.81, 6603.21, 6592.78, 6596.25],
            },
            {
              x: new Date(1538861400000),
              y: [6596.25, 6604.2, 6590, 6602.99],
            },
            {
              x: new Date(1538863200000),
              y: [6602.99, 6606, 6584.99, 6587.81],
            },
            {
              x: new Date(1538865000000),
              y: [6587.81, 6595, 6583.27, 6591.96],
            },
            {
              x: new Date(1538866800000),
              y: [6591.97, 6596.07, 6585, 6588.39],
            },
            {
              x: new Date(1538868600000),
              y: [6587.6, 6598.21, 6587.6, 6594.27],
            },
            {
              x: new Date(1538870400000),
              y: [6596.44, 6601, 6590, 6596.55],
            },
            {
              x: new Date(1538872200000),
              y: [6598.91, 6605, 6596.61, 6600.02],
            },
            {
              x: new Date(1538874000000),
              y: [6600.55, 6605, 6589.14, 6593.01],
            },
            {
              x: new Date(1538875800000),
              y: [6593.15, 6605, 6592, 6603.06],
            },
            {
              x: new Date(1538877600000),
              y: [6603.07, 6604.5, 6599.09, 6603.89],
            },
            {
              x: new Date(1538879400000),
              y: [6604.44, 6604.44, 6600, 6603.5],
            },
            {
              x: new Date(1538881200000),
              y: [6603.5, 6603.99, 6597.5, 6603.86],
            },
            {
              x: new Date(1538883000000),
              y: [6603.85, 6605, 6600, 6604.07],
            },
            {
              x: new Date(1538884800000),
              y: [6604.98, 6606, 6604.07, 6606],
            },
          ],
        },
      ];
      return series_t;
    },
    user() {
      let user = this.$store.getters.currentuser;
      // ////console.log('user is '+JSON.stringify(user));
      if (user && JSON.stringify(user) != "{}") {
        // ////console.log('got it');
        // this.get_allshifts();
        this.cur_dep = user.dep;
        this.get_employees(user.role_list);
        if(user.user_pin=='Ramadhania' || user.user_pin=='aziz'){
          this.show_adminmod=true;
        }
      }
      return user;
    },
    cur_employees_filter() {
      // ////console.log("filtered");
      let jsondata = null;
      if (this.cur_employees ) {
        if(this.cur_roster.length > 0){
        jsondata = this.cur_employees;
        }
      }
      return jsondata;
    },
    roles() {
      let roles = this.$store.getters.get_roles;
      let user = this.$store.getters.currentuser;
      ////console.log("roles are " + roles);
      if (roles.head != undefined) {
        if (!roles.head) {
          // ////console.log(roles);
          this.$destroy();
          this.$router.push("/");
          this.$toastr.e("Anda Gak Punya Akses ke 'Roster'", "Gak Ada Akses!");
        }
      }
      return roles ? roles : {};
    },
  },
};
</script>
<style>
.dd_li:hover {
  background-color: rgb(228, 141, 250, 0.3);
  cursor: pointer;
}
.dd_ul {
  margin-top: 0px;
  position: absolute;
  background: whitesmoke;
  border-radius: 5px;
}
.rounded_shift {
  border-radius: 10px;
  background-color: whitesmoke;
  border: 1px solid gray;
  color: white;
  cursor: pointer;
  padding: 2.5px;
}
/* 
.dd_head {
  position: relative;
}
.dd_li {
  width: 100%;
  height: 100%;
  display: flex;
  padding: 5px;
  justify-content: space-evenly;
  align-items: center;
}
.dd_head a {
  color: white;
  text-decoration: none;
} */
/* 
.dd_head button:focus + ul {
  display: block;
  opacity: 1;
  pointer-events: all;
  transform: translateY(0px);
} */
</style>
