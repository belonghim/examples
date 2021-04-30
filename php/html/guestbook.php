<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['cmd']) == true) {
  header('Content-Type: application/json');
  $host = 'cassandra';
  try {
	  $cluster = Cassandra::cluster()
				 ->withContactPoints($host)->withIOThreads(2)->withConnectTimeout(15)->withRequestTimeout(60)
				 ->build();
	  $session = $cluster->connect("tutorialspoint");
  }catch (Exception $e){
    echo 'Connection exception: ',  $e->getMessage(), "\n";
    exit;
  }

  // Create Guestbook Post
  if ($_GET['cmd'] == 'set') {
	$statement = new Cassandra\SimpleStatement( "INSERT INTO guestbook (itime, message) VALUES ( ? , ?)" );
	$options = array(
		'itime'   => new Cassandra\Timestamp(time()),
		'message' => $_GET['value']
	);
    try {
      $result = $session->execute($statement, array('arguments' => $options));
   }catch (Exception $e){
      echo '	 exception: ',  $e->getMessage(), "\n";
      exit;
    }
    print('{"message": "Updated"}');

  // Get Guestbook Post
  } else {
	$statement = new Cassandra\SimpleStatement( 'SELECT * FROM guestbook' );
    try {
      $result = $session->execute($statement);
    }catch (Exception $e) {
      echo 'SELECT exception: ',  $e->getMessage(), "\n";
      exit;
    }
    $data = array();
	if ($result != null) {
		foreach ($result as $row) {
		  $data[] = "" . $row['itime'] . " : " . $row['message'];
		}
	}
    print('{"data": ' . json_encode($data) . '}');
  }
} else {
  phpinfo();
}
?>