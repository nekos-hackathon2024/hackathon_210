const vm = new Vue({
    el: '#app',
    data: {
        user_mail:"",
        user_pass:"",
        error:"",
    },
    mounted() {
      
    },
    methods: {
        //ユーザーログイン処理
        loginUser() {          
            const url = "../application/api/userLogin.php";
            const data = new FormData();
            data.append('user_mail', this.user_mail);
            data.append('user_pass', this.user_pass);
            
            axios.post(url, data)
              .then(response => {
                // レスポンスを処理するコード
                console.log(response.data);
                // if(response.data == 0){
                //   this.error = "メールアドレスまたはパスワードが間違っています。";
                //   console.log(response.data);
                // }else{
                //     var array = response.data;
                //     console.log(array);
                //     sessionStorage.setItem('user_data',array['user_name']);
                //     alert("ログイン成功");
                // }
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