import axios from "axios";
//import axios_rstb from "axios";

import $store from "../components/vuex_store/store";
export function login(cred) {
    return new Promise((res, rej) => {
        axios
            .post("/api/auth/login", cred)
            .then(response => {
                if(response){res(response.data);
                  apply_credintials(response);

                    }
                axios.defaults.headers.common["Authorization"] =
                    "Bearer " + response.data.token;
            })
            .catch(err => {
                rej(err);
            });
    });
}
function apply_credintials(response) {
  let roles = {
    farmasi: false,
    rwj: false,
    rwi: false
  };
  // console.log('hello');
  // console.log(response.data);

  if (response.data.id != undefined) {
      //console.log('enteed');
      if(response.data.head=='yes'||response.data.head=='*'){
        roles.head=true;
      }else{
        roles.head=false;
      }
      if (response.data.role_list.includes("farmasi")) {
        roles.farmasi = true;
        
      }
      if (response.data.role_list.includes("rwj") || response.data.role_list.includes("dok")) {
        roles.rwj = true;
        
      }
      if (response.data.role_list.includes("oka")) {
        roles.oka = true;
      }
      if( response.data.role_list.includes("*")){
        roles.rwj=true;
        roles.farmasi=true;
        roles.rwi=true;
        roles.oka=true;
      }
    $store.state.roles=roles;
  }
}
export function getlocaluser() {
    //console.log('called auth');
    const userStr = localStorage.getItem("user");
    if (userStr != null) {
        axios.defaults.headers.common["Authorization"] =
            "Bearer " + JSON.parse(userStr).token;
    }

    if (!userStr) {
        //console.log(userStr);

        return null;
    }
    return new Promise((res, rej) => {
        axios
            .post(
                "/api/auth/me",

                {},
                {
                    headers: {
                        Authorization: "Bearer " + JSON.parse(userStr).token
                        // "x-token":JSON.parse(userStr).token
                    }
                }
            )
            .then(response => {
                //console.log(response.data);
                if(response){
                 apply_credintials(response);
                
                res(response.data);
                }
                let user = Object.assign({}, response.data, {
                    token: JSON.parse(userStr).token
                });
                // console.log(user);
                $store.state.currentuser = user;
                // console.log($store.getters.currentuser);
                //localStorage.removeItem('user');
                localStorage.setItem("user", JSON.stringify(user));
            })
            .catch(err => {
                localStorage.removeItem("user");
                res(null);

                // console.log(err)
            });
          //  return JSON.parse(userStr);
    });

    
}
