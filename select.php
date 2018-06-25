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
    "sec"=>1, // Timeout in seconds
    "usec"=>0  // I assume timeout in microseconds //微秒
    )
  );

$rspBuffer = socket_read($socket, 200);
print('firstmsg: ');
print($rspBuffer);
// usleep(500000);
sleep(1);

socket_write($socket, $selection);
$rspBuffer = socket_read($socket, 200);
print('secondmsg: ');
print($rspBuffer);

socket_close($socket);
?>
