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
        //目標金額を更新する
        updateTargetAmount() {          
            const url = "./application/api/setTargetAmount.php";
            const data = new FormData();
            data.append('user_mail', this.user_mail);
            data.append('user_pass', this.user_pass);
            
            axios.post(url, data)
              .then(response => {
                // レスポンスを処理するコード
                if(typeof(response.data) == 0){
                  console.log("目標金額の登録完了!");
                  window.location.assign("./spend_input.html");
                }else if(response.data === 1){
                  console.log("登録に失敗しました");
                  console.log(response.data);
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

    methods: {
      //支出登録処理
      set_amounts_data() {          
          const url = "./application/api/amount_data_set.php";
          const data = new FormData();
          data.append('dow', this.dow);
          data.append('Amount', this.totalNum);
          data.append('user_id',this.user_id);
          data.append('food_ex',this.food_ex);
          data.append('trans_ex',this.trans_ex);
          data.append('enterme_ex',this.enterme_ex);
          data.append('others',this.others);
          data.append('today',this.getDate_today(0));
          
          axios.post(url, data)
            .then(response => {
              // レスポンスを処理するコード
              alert(response.data);
              this.saving = this.exAmount - this.totalNum;
            })
            .catch(error => {
              // エラーハンドリングのコード
              console.error(error);
            });
      },
      get_examount(){
          const url = "./application/api/examount_get.php";
          const data = new FormData();
          data.append('user_id',this.user_id);
          data.append('dow', this.dow);

          axios.post(url, data)
            .then(response => {
              // レスポンスを処理するコード
              this.exAmount = response.data;
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
    },

})