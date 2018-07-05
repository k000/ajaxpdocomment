<html>
<head>
  <title>ajaxとpdoのテスト。参考：Youtube</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container m-5">
    <h1 class="text-center m-5">ajax php comment system</h1>

    <form method="post" id="comment_form">

      <div class="form-group">
        <input type="text" name="comment_name" id="comment_name" class="form-control" placeholder="ニックネーム" />
      </div>

      <div class="form-group">
        <textarea name="comment_content" id="comment_content" class="form-control" placeholder="コメント" rows="5"></textarea>
      </div>

      <div class="form-group">
        <input type="hidden" id="comment_id" name="comment_id" value="0" />
        <input type="submit" id="submit" name="submit"class="btn btn-primary" value="投稿" />
      </div>

    </form>

    <span id="comment_message"></span>
    <div id="display_comment">

    </div>

    <div id="debug"></div>

  </div><!-- end container -->


<script>


    /*
    ページの読み込みが始まる
    HTMLの読み込みが終わる
    $(document).readyが実行
    画像など含めすべてのコンテンツの読み込みが終わる
    $(window).loadが実行
    */
  $(document).ready(function(){


      //コメントフォームのsubmitアクションが発生した時
      $('#comment_form').on('submit',function(event){

        //ページの更新が止まる
        event.preventDefault();
        //パタメータと値を数珠繋ぎにする
        var form_data = $(this).serialize();
        $('#debug').html(form_data);
        //投稿時
        //comment_name=%E3%83%86%E3%82%B9%E3%83%88&comment_content=%E3%83%86%E3%82%B9%E3%83%88%E3%81%A7%E3%81%99&comment_id=0
        //返信時
        //comment_name=%E3%81%82&comment_content=%E3%81%82%E3%81%84%E3%81%86&comment_id=14

        $.ajax({
          method:"POST",
          url:"add_comment.php",
          data:form_data,
          dataType:"JSON",
          success:function(data){
            if(data.error != ''){
              $('#comment_form')[0].reset();
              $('#comment_message').html(data.error);
            }
          }
        });
      });


      load_comment();

/*
      //コメント一覧を表示する
      function load_comment(){

       $.ajax({
        url:"fetch_comment.php",
        method:"POST",
        success:function(data)
        {
         $('#display_comment').html(data);
        }
       })


      }
*/

      //コメント一覧を表示する
      //successは古い書き方なので thenメソッドを使う。1:成功/2:失敗
      function load_comment(){
       $.ajax({
        url:"fetch_comment.php",
        method:"POST",
       })
       .then(
          function(data){
            $('#display_comment').html(data);
          },
          function(){
            alert("読み込み失敗");
          }
       );
      }


      //replyクラスの要素をクリックした時に発火する
      $(document).on('click', '.reply', function(){
        //attrでid属性の値を取得している
       var comment_id = $(this).attr("id");
       //hidden項目を選択したコメントのIDにする。
       //id="'.$row["comment_id"].でreplyクラスは設定されているので
       //親コメントのコメントIDを取得している
       $('#comment_id').val(comment_id);
       //フォーカスを当てる
       $('#comment_name').focus();
      });

  });
</script>


</body>
</html>
