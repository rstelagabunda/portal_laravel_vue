<template>
  <div class="section center">
    <!-- <img class="responsive-img" style="width: 250px;" src="https://i.imgur.com/ax0NCsK.gif" /> -->
    <div class="section"></div>

    <h5 class="indigo-text">Silahkan, loggin ke Akun Anda</h5>
    <div class="section"></div>

    <div class="container">
      <div
        class="z-depth-1 grey lighten-4 row"
        style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;"
      >
        <form class="col s12" method="post" @submit.prevent="authenticate">
          <div class="row">
            <div class="col s12"></div>
          </div>

          <div class="row">
            <div class="input-field col s12">
              <input class="validate" v-model="login_form.user_pin" type="text" name="pin" id="pin" />
              <label for="pin">Enter your pin</label>
            </div>
          </div>

          <div class="row">
            <div class="input-field col s12">
              <input
                class="validate"
                v-model="login_form.password"
                type="password"
                name="password"
                id="password"
              />
              <label for="password">Enter your password</label>
            </div>
            <label style="float: right;">
              <a class="pink-text" href="#!">
                <b>Forgot Password?</b>
              </a>
            </label>
          </div>

          <br />
          <center>
            <div class="row">
              <button
                type="submit"
                name="btn_login"
                class="col s12 btn btn-large waves-effect indigo"
              >Login</button>
            </div>
          </center>
        </form>
      </div>
    </div>
    <!-- <a href="#!">Create account</a> -->
    <div class="section"></div>
    <div class="section"></div>
  </div>
</template>

<script>
import { login } from "../../helpers/auth";
import { router } from "../../routes";
export default {
  name: "login",
  data() {
    return {
      login_form: {
        user_pin: "",
        password: ""
      }
    };
  },
  methods: {
    authenticate() {
      this.$store.dispatch("login");
      login(this.login_form).then(res => {
        //console.log(res);
        if (res) {
          this.$store.commit("loginsuccess", res);
          this.$router.go("/");
        }
      });
      // .catch((err)=>{

      //   })
    }
  }
};
</script>