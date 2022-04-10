<?php 
// SQLインジェクション
?>

<form action="<?php $_SERVER['REQUEST_URI']; ?>" method="POST">
    Shop ID: <input type="text" name="shop_id">
    <input type="submit" value="検索">
</form>

<?php 
if(isset($_POST['shop_id'])) {
    
    $shop_id = $_POST['shop_id'];

    $user = 'test_user';
    $pwd = 'pwd';
    $host = 'localhost';
    $dbName = 'test_phpdb';
    $dsn = "mysql:host={$host};port=8889;dbname={$dbName};";
    $conn = new PDO($dsn, $user, $pwd);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // 入力フォームから意図しないSQLが流されてしまうことをSQLインジェクションと呼ぶ
    // sql処理を実行する際、上記のリスクがあるためqueryメソッドは使用せず、prepareメソッド＋bindvalueのセットが安全。
    $pst = $conn->prepare("select * from test_phpdb.mst_shops where id = :id;");
    $pst->bindValue(':id', $shop_id, PDO::PARAM_INT);
    // 第二引数　渡したい値、第三引数　データ型。仮にint以外のデータ型である場合、sql処理は実行できず停止する。
    $pst->execute();
    $result = $pst->fetch();

    if(count($result) > 0) {
        echo "店舗名は[{$result['name']}]です。";
    }
}
 ?>