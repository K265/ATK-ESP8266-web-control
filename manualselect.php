<?php 
$host = isset($_POST['host']) ? htmlspecialchars($_POST['host']) : '';
$port = isset($_POST['port']) ? htmlspecialchars($_POST['port']) : '';
$selection = isset($_POST['selection']) ? htmlspecialchars($_POST['selection']) : '';

// $buffer = $selection;

$timeout = 5;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (socket_connect($socket, $host, $port) === false) { // 创建连接
  socket_close($socket);
  $message = 'create socket error';
  throw new Exception($message, socket_last_error());
}   

if (socket_write($socket, '7') === false) { // 发包
  socket_close($socket);
  $message = sprintf("write socket error:%s", socket_strerror(socket_last_error()));
  throw new Exception($message, socket_last_error());
}   

socket_set_option(
  $socket,
  SOL_SOCKET,  // socket level
  SO_RCVTIMEO, // timeout option
  array(
    "sec"=>0, // Timeout in seconds
    "usec"=>500000  // I assume timeout in microseconds
    )
  );
$rspBuffer = socket_read($socket, 8192);
echo $rspBuffer;

echo "====";

// sleep(1);
usleep(500000);
socket_write($socket, $selection);
// $rspBuffer = socket_read($socket, 1000, PHP_NORMAL_READ); // 接收回包
$rspBuffer = socket_read($socket, 8192);
echo $rspBuffer;
// if(strstr($rspBuffer, 'opening'))
//   echo strstr($rspBuffer, 'opening');
// if(strstr($rspBuffer, 'closing'))
//   echo strstr($rspBuffer, 'closing');
// while(true){
//     $rspBuffer = socket_read($socket, 8192); // 接收回包
//     print($rspBuffer);
// }

socket_close($socket);
?>
