<template>
<div v-if="all_visit">
    <div>
        <h4 class="center-align">Antrean Poliklinik RS Umum Telaga Bunda Bireuen</h4>
        <h5 class="chip right blue white-text">{{date | formatDate}}</h5>
    </div>
    <div class="col row s12">

        <div class="col s10">
            <table class="table striped highlight">
                <thead>
                    <tr>
                        <th>Nama Dokter</th>
                        <th>Jumlah Antrean</th>

                        <th>Sudah Terlayani</th>

                        <th>Belum Terlayani</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(doc,ind) in docs" :key="ind" :style="'background: linear-gradient(to right, #ff6666 0%,gray '+(doc.Belum/doc.Jumlah)*100+'%'+',gray 100%'+');color:black;font-weight:bold;font-size:18px;padding:2%'" class="animate__animated animate__fadeInUp">
                        <td>
                            <div class="chip">
                                <img :src="'/images/avatar/'+doc.foto" alt class="circle" />
                                <span class="title">dr. {{doc.First_Name}} {{doc.Last_Name?'':doc.Last_Name}} {{doc.Spesialis}}</span>
                            </div>
                        </td>
                        <td class="circle">{{doc.Jumlah}}</td>
                        <td>{{doc.Selesai}}</td>
                        <td>{{doc.Belum}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col s2">
            <div v-for="(each,ind) in 10" :key="ind">
                <div class="card-panel grey lighten-5 z-depth-1">
                    <div class="row valign-wrapper col row s12">
                        <div>
                            <img src="/images/office.jpg" alt="" class="circle" style="width:100px;height:100px"> <!-- notice the "circle" class -->
                        </div>
                        <br>

                    </div>
                    <span class="black-text">
                        This is
                    </span>
                </div>
            </div>

        </div>
    </div>

</div>
</template>

<script>
export default {
    data() {
        return {
            docs: [],
            date: new Date()
        };
    },
    created() {
        this.fetch_freshdata();
        this.timer = setInterval(this.fetch_freshdata, 30000);

        // this.timer = setInterval(this.fetch_panggil, 1000);

    },
    mounted() {},
    computed: {
        all_visit() {
            let temp = this.$store.getters.get_allvisit;
            let docs = [];
            // console.log(temp);
            if (temp != undefined) {
                temp.forEach(element => {
                    // console.log(element.Spesialis);
                    if (element.Spesialis != null) {
                        let exist = 0;
                        docs.forEach(element1 => {
                            // console.log(element.Spesialis);
                            if (element1.First_Name == element.First_Name) {
                                element.Selesai == 1 ?
                                    (element1.Selesai += 1) :
                                    (element1.Belum += 1);
                                element1.Jumlah += 1;
                                exist = 1;
                            }
                        });
                        if (exist == 0) {
                            docs.push({
                                First_Name: element.First_Name,
                                Selesai: element.Selesai == 1 ? 1 : 0,
                                Belum: element.Selesai == 1 ? 0 : 1,
                                Jumlah: 1,
                                Last_Name: element.Last_Name,
                                Spesialis: element.Spesialis,
                                foto: element.foto
                            });
                        }
                    }
                });
            }
            this.docs = docs;
            this.docs.sort(function (a, b) {
                return parseInt(a.Jumlah) < parseInt(b.Jumlah);
            });
            return temp;
        }
    },
    beforeDestroy() {
        clearInterval(this.timer);
    },
    methods: {
        fetch_freshdata() {
            this.$store.dispatch("get_allvisit", {
                search: "",
                dok: "",
                tgl: this.date.yyyymmdd()
            });
        },
        fetch_panggil() {

        }
    }
};
</script>

<style>
</style>
