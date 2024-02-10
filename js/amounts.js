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
        ex_amount: 0,
        saving: 0,
        exAmount: 0,
    },
    mounted() {
      this.today = this.getDate_today(1);
      this.user_id = sessionStorage.getItem('id');
      this.user_name = sessionStorage.getItem('name');
      this.ex_amount = sessionStorage.getItem('targetAmount');
      this.dow = this.getDate_dow();
      this.get_examount();
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