const vm = new Vue({
    el: '#app',
    data: {
        user_id: 0,
        user_name: "",
        food_ex: 0,
        trans_ex: 0,
        enterme_ex: 0,
        others: 0,
        today: "今日",
        dow: "",
        ex_amount: 2000,
    },
    mounted() {
      this.today = this.getDate_today(1);
      this.user_id = sessionStorage.getItem('id');
      this.user_name = sessionStorage.getItem('name');
      this.dow = this.getDate_dow();
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
                  sessionStorage.setItem('name',response.data['user_name']);
                  window.location.assign("./spending.html");
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
        },
        //今日の日付を取得する関数、0でyyyy-mm-dd。1でyyyy年mm月dd日
        getDate_today(mode){
            var dt = new Date();
            var y = dt.getFullYear();
            var m = ('00' + (dt.getMonth()+1)).slice(-2);
            var d = ('00' + dt.getDate()).slice(-2);
            if(mode == 0){
                return (y + '-' + m + '-' + d);
            }else{
                return (y + '年' + m + '月' + d + '日');
            }
            
        },
        //曜日を取得する関数
        getDate_dow(){
            var WeekChars = [ "日", "月", "火", "水", "木", "金", "土" ];
            var dObj = new Date();
            var wDay = dObj.getDay();

            console.log(WeekChars[wDay]);

            return WeekChars[wDay];
        }
      },
      computed: {
        totalNum(){
            return this.food_ex + this.trans_ex + this.enterme_ex + this.others
        }
      }
})