
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crawler</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.1/examples/cover/">

    <link href="https://getbootstrap.com/docs/4.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/4.1/examples/cover/cover.css" rel="stylesheet">
    <style type="text/css">
        body{box-shadow:none;}
        .cover-container{z-index: 2;max-width: 60%;}
        .masthead{margin-bottom: 2rem!important;}
        .bg{background-image: url(static/images/bg.png);height: 100vh;width: 100vw;position: fixed;background-repeat: no-repeat;background-size: cover;opacity: 0.1;z-index: 1;box-shadow: inset 0 0 5rem rgb(0 0 0 / 50%);}
        .nav-pills .nav-link.active, .nav-pills .show>.nav-link {color: #fff;background-color: #ff8d00;}
        #pills-tabContent{border: 1px solid #ccc;padding: 20px;}
        .lead{margin: 0 auto;}
        .house-price{font-size: 30pt;}
        .card{background: none;border:1px solid #fff;margin: 10px;}
        .modal-dialog {max-width: 80%;color:#000;}
        .modal-backdrop {z-index: 0;}
        .text-sample{cursor: pointer;padding: 2px;background: #ccc;border-radius: 5px;color:#000;}
        .ellipsis{overflow:hidden;white-space: nowrap;text-overflow: ellipsis;}
        iframe{width: 100%;height: 80vh;border: 1px solid #ccc;}
    </style>
  </head>

  <body class="text-center">
    
    <div id="container" class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
      <header class="masthead mb-auto">
        <div class="inner">
          <h3 class="masthead-brand">Crawler</h3>
        </div>
      </header>

      <main role="main" class="inner cover" >
        <h1 class="cover-heading">URL to Screenshot</h1>
        <p class="lead">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="byMonth">
                    <div class="form-row start-search">
                        <div class="form-group col-md-12">
                          <label>Domain</label>
                          <div class="input-group">
                            <input type="text" class="form-control" placeholder="www.google.com" v-model="form.url">
                            
                          </div>
                        </div>
                        
                        <div class="form-group col-md-12">
                            <span class="text-sample" @click="setText('url','https://www.w3schools.com/')">https://www.w3schools.com/</span>
                            <span class="text-sample" @click="setText('url','https://www.etmall.com.tw/')">https://www.etmall.com.tw/</span>
                            <span class="text-sample" @click="setText('url','https://tw.news.yahoo.com/weather/')">https://tw.news.yahoo.com/weather/</span>
                            <span class="text-sample" @click="setText('url','https://tw.buy.yahoo.com/')">https://tw.buy.yahoo.com/</span>
                            <span class="text-sample" @click="setText('url','https://github.com/')">https://github.com/</span>
                            
                            
                        </div>
                        <div class="form-group col-md-12">
                            <p class="lead">
                                <a href="#" class="btn btn-lg btn-secondary" @click="startUrlCrawler">Start!</a>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div id="loading" style="display: none"><img style="width:20px" src="static/images/loading.gif">Processing..</div>

                <h3>List</h3>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <nav aria-label="Page navigation">
                          <ul class="pagination justify-content-center">
                            <li class="page-item">
                                <div class="page-link" @click="setPage('prev')">Previous</div>
                            </li>
                            <li :class="'page-item '+setClass('page',index)" v-for="index in page.total_page">
                                <div class="page-link" v-show="setShow('pageRange',index)" @click="getPastUrlData(index-1)">##index##</div>
                            </li>
                            <li class="page-item">
                                <div class="page-link" @click="setPage('next')">Next</div>
                            </li>
                          </ul>
                        </nav>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                          <label>Title</label>
                          <div class="input-group">
                            <input type="text" class="form-control" v-model="form.filter_title">
                          </div>
                    </div>
                    <div class="form-group col-md-2">
                          <label>Desc</label>
                          <div class="input-group">
                            <input type="text" class="form-control" v-model="form.filter_desc">
                          </div>
                    </div>
                    <div class="form-group col-md-2">
                          <label>Create At</label>
                          <div class="input-group">
                            <input type="date" class="form-control" v-model="form.filter_at">
                          </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label style="opacity: 0">.</label>
                        <div class="input-group">
                            <a href="#" class="btn btn-secondary btn-block" @click="setText('clear','')">Clear</a>
                        </div>
                    </div>
                    <div class="form-group col-md-2">
                        <label style="opacity: 0">.</label>
                        <div class="input-group">
                            <a href="#" class="btn btn-info btn-block" @click="getPastUrlData(0)">Filter</a>
                        </div>
                    </div>
                    
                </div>
                <div class="card done-search" v-if="pastData.length>0" v-for="data in pastData">
                  <div class="card-body">
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <div style="cursor: pointer;font-size: 24pt" @click="showDetail('cache/'+data.dir+'/cache.html',data.dir,data.status)">
                                <span v-if="data.title">##data.title##</span>
                                <span v-else>##data.url##</span>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <small style="text-align: left">##data.desc##</small>
                        </div>
                        <div class="form-group col-md-6" style="border-top: 1px solid #fff;padding-top: 20px;">
                            <!--<a :href="'cache/'+data.dir+'/cache.html'" target="blank">link</a>-->
                            <span style="cursor: pointer;" v-if="data.status=='Finished'&&data.screenshot=='none'" @click="showDetail('cache/'+data.dir+'/cache.html',data.dir,data.status)">Click to Generate Snapshoot!</span>
                            <div style="overflow: hidden;max-height: 150px" v-else-if="data.screenshot!='none'"><img style="max-width: 250px" :src="data.screenshot"></div>
                            
                        </div>
                        <div class="form-group col-md-6" style="border-top: 1px solid #fff;padding-top: 20px;">
                            <img v-if="data.status=='Queued'" style="width:20px" src="static/images/loading.gif">
                            ##data.status##<br>
                            ##data.created_at##
                        </div>
                    </div>
                  </div>
                </div>
                
                
            </div>
            {{ csrf_field() }}
        </p>
        
      </main>

      <footer class="mastfoot mt-auto">
        <div class="inner">
          <p>Cover template for <a href="https://getbootstrap.com/">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a>. Crawler by Peter.</p>
        </div>
      </footer>
        <!-- Modal -->
        <div class="modal fade" id="detail-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Page Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <div style="overflow: hidden;max-height: 150px"><img style="max-width: 250px;border:1px solid #000" v-if="modal.detailData.screenshot!='none'" :src="modal.detailData.screenshot"></div>
                        </div>
                        <div class="form-group col-md-8">
                            <a style="color:#000;" target="blank" :href="'cache/'+modal.detailData.dir+'/cache.html'">##modal.detailData.title##</a><br>
                            ##modal.detailData.desc##<br>
                            <a style="color:#000;" target="blank" :href="'cache/'+modal.detailData.dir+'/cache.html'">Cached Page</a>
                        </div>
                    </div>
                    
                    
                </div>
                <div id="wait-ss" style="display: none"><img style="width:20px" src="static/images/loading.gif">Wait ScreenShot..</div>
                <div id="done-ss" style="display: none">ScreenShot Done!</div>
                <iframe id="detail-frame" :src="modal.src"></iframe>
                <!--<div style="max-height: 80vh;background: #fff;">
                    <div id="detail-frame" v-html="modal.detailData.html"></div>
                </div>-->
                
              </div>
            </div>
          </div>
        </div>
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
            setTimeout(function(){
                app.getPastUrlData(0);
            }, 10);             
        },
        data: {
            form:{
                'url':'www.google.com',
                'page':0,
                'dir':'',
                '_token': $('[name=_token]').val(),
                'filter_title':'',
                'filter_desc':'',
                'filter_at':'',
            },
            pastData:{
            },
            page:{
            },
            results:{
            },
            modal:{
                'detailData':{},
                'src':''
            }
            
        },
        methods:{
            startUrlCrawler: function(){
                $('#loading').show();
                app.ajax('/startUrlCrawler',app.$data.form,null,function(res){
                    $('#loading').hide();
                    if(res.code==200){
                        app.$data['results'] = res.data;
                        app.$data['form']['dir'] = res.data['dir'];
                        setTimeout(function(){
                            app.setText('clear','');
                            app.getPastUrlData(0);
                            app.startBackground(res.data['dir']);
                        }, 10);  
                    }else{
                        app.getPastUrlData(0);
                    }
                });
            },
            startBackground: function(dir){
                postData = {'_token':app.$data['form']['_token'],'dir':dir};
                app.ajax('/startBackground',app.$data.form,null,function(res){
                    app.$data['results'] = res.data;
                    setTimeout(function(){
                        app.getPastUrlData(0);
                    }, 10);  
                });
            },
            getPastUrlData: function(page){
                app.$data['form']['page'] = page;
                app.ajax('/getPastUrlData',app.$data.form,null,function(res){
                   app.$data['pastData'] = res.data['pastData'];
                   app.$data['page'] = res.data['page'];
                });
            },
            showDetail: function(url,dir,status){
                if(status=='Queued'){
                    alert('This website is in queued, Please Wait.');return;
                }
                app.$data['modal']['src'] = url;
                postData = {'dir':dir,'_token':app.$data['form']['_token']};
                app.ajax('/getUrlDetail',postData,null,function(res){
                   app.$data['modal']['detailData'] = res.data['detailData'][0];
                    $('#detail-modal').modal('show');
                    if(app.$data['modal']['detailData']['screenshot']!='none'){
                        $('#done-ss').show();
                        return;
                    }
                    $('#wait-ss').show();
                    $('#done-ss').hide();
                    setTimeout(function(){
                        var frame = document.querySelector('#detail-frame');
                        var dir = frame.contentWindow.document.body.querySelector("#iframe-dir").value;
                        var imgBase64 = frame.contentWindow.document.body.querySelector("#iframe-pic").value;
                        postData = {'dir':dir,'imgBase64':imgBase64,'_token':app.$data['form']['_token']};
                        app.ajax('/saveScreenshot',postData,null,function(res){
                            if(res.code==200){
                                app.getPastUrlData(0);
                                app.showDetail(url,dir);
                                $('#wait-ss').hide();
                                $('#done-ss').show();
                            }else{
                                app.showDetail(url,dir);
                            }
                        });
                    }, 8000);  
                });
                
                
            },
            setPage:function(type){
                if(type=='prev'&&app.$data['form']['page']>0){
                    page = app.$data['form']['page']-1;
                }else if(type=='next'&&app.$data['form']['page']<app.$data['page']['total_page']-1){
                    page = app.$data['form']['page']+1;
                }else{
                    return;
                }
                app.getPastUrlData(page);
            },
            setClass:function(type,value){
                if(type=='page'&&app.$data['form']['page']+1==value){
                    return 'active';
                }
                return '';
            },
            setShow:function(type,value){
                if(type=='pageRange'){
                    set = 10;
                    nowPage = app.$data['form']['page']+1;
                    totalPage = app.$data['form']['total_page'];
                    if(nowPage>5){
                        if(value>=nowPage-4&&value<=nowPage+4){
                            return true;
                        }
                    }else if(nowPage<set&&value<10){
                        return true;
                    }
                }
                return false;
            },
            setText:function(type,url){
                if(type=='url'){
                    app.$data['form']['url'] = url;
                }else if(type=='clear'){
                    app.$data['form']['filter_title'] = '';
                    app.$data['form']['filter_desc'] = '';
                    app.$data['form']['filter_at'] = '';
                }
            },
            ajax: function (url,data,detail,callback){
                return this.$http.post(url, data,{emulateJSON: true})
                .then(function (res){
                  var retBody = res.body;
                  if(retBody.code==200){
                    callback(retBody);
                  }else{
                    console.log(retBody.code);
                    callback(retBody);
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
