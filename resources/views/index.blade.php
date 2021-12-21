
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Crawler</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.1/examples/cover/">

    <link href="https://getbootstrap.com/docs/4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/4.1/examples/cover/cover.css" rel="stylesheet">
    <style type="text/css">
        body{box-shadow:none;}
        .cover-container{z-index: 2}
        .masthead{margin-bottom: 5rem!important;}
        .bg{background-image: url(static/images/bg.png);height: 100vh;width: 100vw;position: fixed;background-repeat: no-repeat;background-size: cover;opacity: 0.2;z-index: 1;box-shadow: inset 0 0 5rem rgb(0 0 0 / 50%);}
        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {color: #fff;background-color: #ff8d00;}
        #pills-tabContent{border: 1px solid #ccc;padding: 20px;}
        .lead{margin: 0 auto;}
        .house-price{font-size: 30pt;}
        .card{background: none;border:1px solid #fff;margin: 10px;}
    </style>
  </head>

  <body class="text-center">
    
    <div id="container" class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
      <header class="masthead mb-auto">
        <div class="inner">
          <h3 class="masthead-brand">Crawler</h3>
          <nav class="nav nav-masthead justify-content-center">
            <a class="nav-link active" href="#">我能買什麼樣的房子?</a>
          </nav>
        </div>
      </header>

      <main role="main" class="inner cover" >
        <h1 class="cover-heading">一鍵找出適合你的物件</h1>
        <p class="lead">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#byMonth">依每月可負擔</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#byHome">依家庭月收</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#byPrice">依總價</a>
              </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div id="loading" style="display: none"><img style="width:20px" src="static/images/loading.gif">載入中..</div>
                <div class="tab-pane fade show active" id="byMonth">
                    <div class="form-row start-search">
                        <div class="form-group col-md-4">
                          <label>可負擔金額/月</label>
                          <div class="input-group">
                            <input type="number" class="form-control" placeholder="3.5" v-model="byMonthForm.payPerMonth">
                            <div class="input-group-prepend">
                              <span class="input-group-text">萬</span>
                            </div>
                          </div>
                        </div>
                        <div class="form-group col-md-4">
                          <label>預計年利率</label>
                          <div class="input-group">
                            <input type="number" class="form-control" placeholder="1.4" v-model="byMonthForm.loanRate">
                            <div class="input-group-prepend">
                              <span class="input-group-text">%</span>
                            </div>
                          </div>
                        </div>
                        <div class="form-group col-md-4">
                          <label>還款期數</label>
                          <div class="input-group">
                            <input type="number" class="form-control" placeholder="30" v-model="byMonthForm.loanPeriods">
                            <div class="input-group-prepend">
                              <span class="input-group-text">年</span>
                            </div>
                          </div>
                        </div>
                        <div class="form-group col-md-12">
                            <p class="lead">
                                <a href="#" class="btn btn-lg btn-secondary" @click="searchByMonth">評估</a>
                            </p>
                        </div>
                    </div>
                    <div class="form-row done-search" style="display: none">
                        <div class="form-group col-md-12">
                            <p>加油點可負擔的房價是: 
                                <span class="house-price">##byMonthForm.result.maxHousePrice##</span> 萬<br>
                            (##byMonthForm.result.maxPayPerMonth##/月,
                            頭期: ##byMonthForm.result.maxFirstMoney## 萬,
                            ##byMonthForm.result.maxMeta##)</p>
                            <p>輕鬆點的房價是:
                                <span class="house-price">##byMonthForm.result.minHousePrice##</span> 萬<br>
                            (##byMonthForm.result.minPayPerMonth##/月,
                            頭期: ##byMonthForm.result.minFirstMoney## 萬,
                            ##byMonthForm.result.minMeta##)</p>
                        </div>
                        <div class="form-group col-md-12">
                            <p class="lead">
                                <a href="#" class="btn btn-lg btn-secondary" @click="searchReset">再次評估</a>
                            </p>
                        </div>
                    </div>
                    
                </div>

                <div class="tab-pane fade" id="byHome">...</div>
                <div class="tab-pane fade" id="byPrice">...</div>
                <div class="card done-search" v-for="data in byMonthForm.houseData">
                  <div class="card-body">
                    <div class="form-row done-search">
                        <div class="form-group col-md-6">
                            ##data[1]####data[2]##<br>
                            ##data[3]##<br>
                            ##data[11]##<br>
                            ##data[16]##房##data[17]##廳##data[18]##衛<br>
                            交易日期:##data[7]##<span v-if="data[14]!=''"> (建成:##data[14]##)</span><br>
                        </div>
                        <div class="form-group col-md-6">
                            總價: ##numFormat('w',data[21])## 萬<br>
                            單價: ##numFormat('w',data[35])## 萬/坪<br>
                            建坪: ##numFormat('p',data[34])##<br>
                        </div>
                    </div>
                    
                  </div>
                </div>
            </div>
        </p>
        
      </main>

      <footer class="mastfoot mt-auto">
        <div class="inner">
          <p>Cover template for <a href="https://getbootstrap.com/">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a>.</p>
        </div>
      </footer>

    </div>
    <div class="bg"></div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://getbootstrap.com/docs/4.1/assets/js/vendor/popper.min.js"></script>
    <script src="https://getbootstrap.com/docs/4.1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-resource@1.5.3/dist/vue-resource.min.js"></script>
    
    <script>
      var app = new Vue({
        delimiters: ["##", "##"],
        el: '#container',
        created: function (){
            //this.searchByMonth()
        },
        data: {
            byMonthForm:{
                'payPerMonth':2,
                'loanRate':1.4,
                'loanPeriods':30,
                'csrfmiddlewaretoken': $('[name=csrfmiddlewaretoken]').val(),
                'result':{
                    'maxHousePrice':0,
                    'maxPayPerMonth':0,
                    'maxFirstMoney':0,
                    'maxMeta':'',
                    'minHousePrice':0,
                    'minPayPerMonth':0,
                    'minFirstMoney':0,
                    'minMeta':'',
                },
                'houseData':{

                }
            },
            
        },
        methods:{
            ajax: function (url,data,detail,callback){
                return this.$http.post(url, data,{emulateJSON: true})
                .then(function (res){
                  var retBody = res.body;
                  if(retBody.code==200){
                    callback(retBody);
                  }else{
                    console.log('fail');
                    console.log(retBody);
                  }
                })
                .catch(function (res) {
                  console.log('fail');
                })   
            }
        },
        mounted: function () {
        }
      });
    </script>
  </body>
</html>
