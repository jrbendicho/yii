<?php
echo "hello world\n\n";
$con = mysql_connect("localhost","root",'727$hubfox');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
mysql_select_db("richard_hubfox", $con);  
if(isset($argv[1]))
    $table = $argv[1];
else{
    print_r("\n Missing argument table name use like: php mGenSingle.php TABLE\n\n");
    die;
}
        $output = "<?php\n/**\n*Auto Generated Model\n*\n*Program created By Sal Santoro sssantoro07@gmail.com\n*\n**********************************************************************************/\n\n";
        $output .= "Class ".ucwords($table)." extends CActiveRecord\n{\n";
        $model = "\n\n\tpublic static function model($"."className=__CLASS__)\n\t{\n\t\treturn parent::model($"."className);\n\t}\n\n";
        $tname = "\n\tpublic function tableName()\n\t{\n\t\treturn '$table';\n\t}\n\n";
        $dbconn = "\npublic function getDbConnection() {
            \nreturn Yii::app()->db2;
        \n}";
        $primaryKey = "";
        $sql = "DESCRIBE $table";
        $result = mysql_query($sql);
        $columns = array();
        $rules = "\n\n\tpublic function rules()\n\t{\n\t\treturn array(\n\t\t\tarray(\n\t\t\t'";
        $attrs = "\n\n\tpublic function attributeLabels()\n\t{\n\t\treturn array(\n\t\t";
        while($row = mysql_fetch_array($result,MYSQL_ASSOC))
          {
            if($row['Key'] == 'PRI')    {
                $primaryKey = "\n\tpublic function primaryKey()\n\t{\n\t\treturn '".$row['Field']."';\n\t}\n\n";
            }
            $output .= "\n\tpublic $".$row['Field'].";";
            $rules .= $row['Field'].",";
            $attrs .= "\n\t\t'".$row['Field']."'=>'".ucwords(str_replace('_',' ',$row['Field']))."',";
          }
          $rules .= "','safe'));\n\t}\n";
          $attrs = substr($attrs,0,-1);
          $attrs .= ");\n\t}";
          $output .= $model;
          $output .= $tname;
          $output.= $dbconn;
          $output .= $primaryKey;
          $output .= $rules;
          $output .= $attrs."\n\n}\n?>";
          $fp = fopen(ucwords($table).'.php', 'w');
            fwrite($fp, $output);
            fclose($fp);
          echo "Completed writing ".ucwords($table)."\n";
  
mysql_close($con);
?>