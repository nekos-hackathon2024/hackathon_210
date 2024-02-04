const vm = new Vue({
    el: '#app',
    data: {
        user_mail:"",
        user_pass:"",
        user_name:"",
        error:"",
    },
    mounted() {
      
    },
    methods: {
        //ユーザー新規登録処理
        createUser() {          
            const url = "../application/api/userCreate.php";;
            const data = new FormData();
            data.append('user_mail', this.user_mail);
            data.append('user_pass', this.user_pass);
            data.append('user_name', this.user_name);
            
            axios.post(url, data)
              .then(response => {
                // レスポンスを処理するコード
                console.log(response.data);
                if(response.data == 0){
                  this.error = "このメールアドレスは既に使用されています。";
                }else{
                  alert("成功");
                  window.location.assign("./complete.php");
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