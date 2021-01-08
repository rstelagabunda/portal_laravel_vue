
<template>
  <div id="main">
    <Header />
    <div class="row">
      <div class="col s12 animate__animated animate__fadeInUp animate__delay-1s">
        <!-- Teal page content  -->
        <div >
          <keep-alive>
            <router-view :key="$route.fullPath" ></router-view>
          </keep-alive>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Header from "./Header";
// import Login from './login'
export default {
  name: "main-app",
  components: {
    Header
  },
  
};
</script>