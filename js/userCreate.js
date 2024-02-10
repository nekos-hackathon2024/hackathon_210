const vm = new Vue({
    el: '#app',
    data: {
        user_mail:"",
        mail_err:"",

        user_pass:"",
        pass_err:"",

        user_name:"",
        name_err:"",
    },
    mounted() {
      
    },
    methods: {
        //ユーザー新規登録処理
        createUser() {          
            const url = "./application/api/userCreate.php";
            const data = new FormData();
            data.append('user_mail', this.user_mail);
            data.append('user_pass', this.user_pass);
            data.append('user_name', this.user_name);

            if(this.isInValid()){
              return 0;
            }
            
            axios.post(url, data)
              .then(response => {
                // レスポンスを処理するコード
                console.log(response.data);
                if(response.data == 0){
                  alert("新規登録完了");
                  window.location.assign("./login.html");
                }else if(response.data == 1){
                  this.error = "このメールアドレスは既に使用されています。";
                }else {
                  console.error("例外エラー");
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
        },
        //バリデーションのメソッド
        isInValid(){
          //エラーカウンタ
          var error = 0;
          //文字数が4文字以下の場合、trueを返す
          if(this.user_name.length < 4){
            error++;
            this.name_err = "名前は4文字以上入力してください";
          }

          //メールアドレスの形式チェック
          if(!this.user_mail.match(/.+@.+\..+/)){
            error++;
            this.mail_err = "メールアドレスの形式が不正です";
          }

          //パスワードの形式チェック
          if(!this.user_pass.match(/^(?=.*?[a-z\d])(?=.*?[!-\/:-@[-`{-~])[!-~]{8,16}$/)){
            error++;
            this.pass_err = "記号を含んだ半角英数8桁以上16桁以下で入力してください。";
          }

          if(error != 0){
            return true;
          }else{
            return false;
          }
        }
      },
      computed:{
        
      }
})