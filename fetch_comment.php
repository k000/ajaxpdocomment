<?php

    //親コメントを取得する
    $connect = new PDO('mysql:host=localhost;dbname=ajaxcomment','admin','');
    $sql = "
      select * from comment where parent_comment_id = '0' order by comment_id desc
    ";

    $state = $connect->prepare($sql);
    $state->execute();
    //全ての結果行を含む配列を返す
    $result = $state->fetchAll();
    $output = '';

    foreach($result as $row){
        $output .= '
          <div class="card mt-3">
            <div class="card-header bg-info">名前：<B> '.$row["name"].'</b> 日時： <i> '.$row["date"].' </i></div>
            <div class="card-body">'.$row["comment"].'</div>
            <div class="card-footer" align="right"><button type="button" class="btn-sm btn-info reply" id="'.$row["comment_id"].'">返信</button></div>
          </div>
        ';
        //返信コメントを取得する。引数に親コメントのコメントIDを渡す
        //まず親コメントを表示し、そのIDから返信コメントを表示する
        $output .= get_reply_comment($connect,$row["comment_id"]);
    }

    echo $output;


    //返信コメントの取得
    function get_reply_comment($connect,$parent_id = 0, $marginLeft = 0){

      $output = '';

      $sql = "
          select * from comment where parent_comment_id = '".$parent_id."'
      ";

      $state = $connect->prepare($sql);
      $state->execute();

      $result = $state->fetchAll();
      //本来selectの件数結果取得には推奨されていない。
      //update delete insert などの実行件数を取得することが目的
      $count = $state->rowCount();

      if($parent_id == 0){
        $marginLeft = 0;
      }else{
        $marginLeft = $marginLeft + 48;
      }

      if($count > 0){
        foreach($result as $row){

          $output .= '
              <div class="card mt-1" style="margin-left:'.$marginLeft.'px">
                <div class="card-header">名前：<b>'.$row["name"].'</b> 日時：'.$row["date"].'</i></div>
                <div class="card-body">'.$row["comment"].'</div>
                <div class="card-footer" align="right"><button type="button" class="btn-sm btn btn-info reply" id="'.$row["comment_id"].'">返信</button></div>
              </div>
          ';
            //さらに返信コメントに対する返信コメントがあれば表示する
            $output .= get_reply_comment($connect,$row["comment_id"],$marginLeft);
        }//end foreach
      }//end if

      return $output;


    }

 ?>
