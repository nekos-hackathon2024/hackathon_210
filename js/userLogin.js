const vm = new Vue({
    el: '#app',
    data: {
        user_mail:"",
        user_pass:"",
        error:"",
        user_name:"",
    },
    mounted() {
      
    },
    methods: {
        //ユーザーログイン処理
        loginUser() {          
            const url = "./application/api/userLogin.php";
            const data = new FormData();
            data.append('user_mail', this.user_mail);
            data.append('user_pass', this.user_pass);
            
            axios.post(url, data)
              .then(response => {
                // レスポンスを処理するコード
                if(typeof(response.data) == 'object'){
                  console.log("成功時分岐");
                  console.log(response.data);
                  sessionStorage.setItem('id',response.data['user_id']);
                  sessionStorage.setItem('name',response.data['user_name']);
                  sessionStorage.setItem('targetAmount',response.data['targetAmount']);
                  window.location.assign("./spend_input.html");
                }else if(response.data === 1){
                  console.log("失敗時分岐");
                  console.log(response.data);
                  this.error = "メールアドレスまたはパスワードが間違っています。";
                } else {
                  console.log("異常終了");
                }
              })
              .catch(error => {
                // エラーハンドリングのコード
                console.error(error);
              });
        },
        // 特定のGETパラメータが存在するかどうかを確認する関数
        checkQueryParamExists(paramName) {
          const searchParams = new URLSearchParams(window.location.search);
          return searchParams.has(paramName);
        },
        //特定のGETパラメータを取得する関数
        getQueryParam(paramName) {
          const urlParams = new URLSearchParams(window.location.search);
          return urlParams.get(paramName);
        }
      },
})