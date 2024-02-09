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
                console.log(response.data)
                // レスポンスを処理するコード
                if(response.data === 0){
                  console.log("失敗時分岐");
                  console.log(response.data);
                  this.error = "メールアドレスまたはパスワードが間違っています。";
                }else{
                  console.log("成功時分岐");
                  console.log(response.data);
                  sessionStorage.setItem('name',response.data['user_name']);
                  this.user_name = response.data['user_name'];
                  window.location.assign("./spending.html");
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