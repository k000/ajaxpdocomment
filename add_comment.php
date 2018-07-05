<?php

/*
  ajax通信がうまく行われていないときは、phpファイルに構文エラーがないかどうか確認する
  直接このファイルにアクセスしてエラーがないかどうかを確認する
*/


  $connect = new PDO('mysql:host=localhost;dbname=ajaxcomment','admin','');



  $error = '';
  $comment_name = '';
  $comment_content = '';

  //バリデーション
  //名前が空欄だった場合
  if(empty($_POST['comment_name'])){
      //errorに名前未入力を入れる
      $error .= '<P>名前が入力されていません</P>';
  }else{
      //comment_nameにフォームで入力された値を設定する
      $comment_name = $_POST['comment_name'];
  }

  //本文が空欄だった場合
  if(empty($_POST['comment_content'])){
      //errorにメッセージを追加する
      $error .= '<P>本文を入力してください</P>';
  }else{
      $comment_content = $_POST['comment_content'];
  }


  //エラーがない場合のみ、データを保存する
  if($error == ""){
    $sql = "
              insert into comment (parent_comment_id,comment,name)
                values (:parent_comment_id,:comment,:name)
            ";
    //sql文をdbに対して発行するが、sqlの一部を変数のようにしておき
    //あとでそこを埋められるようにしている
    $result = $connect->prepare($sql);
    //実行する
    $result->execute(
      //先ほどのプレイスホルダーの値をここで設定する
      array(
        //コメントIDはAIなので投稿のときはインクリメントされる
        //返信の場合は、親コメントのコメントIDが付与される
        ':parent_comment_id' => $_POST['comment_id'],
        ':comment' => $comment_content,
        ':name' => $comment_name,
      )
    );
    //最後に成功メッセージを入れる
    $error = '<label class="text-success">投稿完了</label>';
  }

  $data = array(
    'error' => $error
  );

  echo json_encode($data);

 ?>
