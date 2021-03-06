<?php
// var_dump($_POST);

session_start();
include("functions.php");
check_session_id();

if (
  !isset($_POST['todo']) || $_POST['todo'] == '' ||
  !isset($_POST['deadline']) || $_POST['deadline'] == ''
) {
  // 項目が入力されていない場合はここでエラーを出力し，以降の処理を中止する
  echo json_encode(["error_msg" => "no input"]);
  exit();
}

// 受け取ったデータを変数に入れる
$todo = $_POST['todo'];
$deadline = $_POST['deadline'];
// var_dump($_FILES);


if (!isset($_FILES['upfile']) && $_FILES['upfile']['error'] != 0) {
exit('Error:画像が送信されていません');
} else {
    // var_dump($_FILES);
    $uploaded_file_name = $_FILES['upfile']['name'];
    $temp_path = $_FILES['upfile']['tmp_name'];
    $directory_path = 'upload/';

    $extension = pathinfo($uploaded_file_name, PATHINFO_EXTENSION);
    $unique_name = date('YmdHis').md5(session_id()) . "." . $extension;
    $filename_to_save = $directory_path . $unique_name;
    // var_dump($filename_to_save);
    $img='';
    if (!is_uploaded_file($temp_path)) {
        exit('Error:画像がありません');
    } else {
        if (!move_uploaded_file($temp_path, $filename_to_save)) {
            exit('Error:アップロードできませんでした'); // 画像の保存に失敗
        } else {
            chmod($filename_to_save, 0644); // 権限の変更
            // var_dump($filename_to_save);
$pdo = connect_to_db();

$sql = 'INSERT INTO todo_table(id, todo, deadline, image, created_at, updated_at) VALUES(NULL, :todo, :deadline, :image, sysdate(), sysdate())';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':todo', $todo, PDO::PARAM_STR);
$stmt->bindValue(':deadline', $deadline, PDO::PARAM_STR);
$stmt->bindValue(':image', $filename_to_save, PDO::PARAM_STR);
$status = $stmt->execute();
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  header("Location:todo_input.php");
  exit();
}
        }
    }

}

