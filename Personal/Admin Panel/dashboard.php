<?php
// Import server.php file to process login requests
include('server.php');

// If the logout button is pressed, destroy active session removing all session values and cookies and redirect to login page
if (isset($_GET['logout'])) {
  session_destroy();
  unset($_SESSION['email']);
  header("location: login.php");
}

// If the session email is not set, redirects user to main page
if (!isset($_SESSION['email'])) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login.php');
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="assets/css/stars.css">
    <link rel="stylesheet" href="assets/css/main.css">

    <title>Dashboard</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
  </head>
  <body style="overflow-x: hidden">

    <!-- Background -->
    <div class="page-bg"></div>
    <div class="animation-wrapper">
      <div class="particle particle-1"></div>
      <div class="particle particle-2"></div>
      <div class="particle particle-3"></div>
      <div class="particle particle-4"></div>
    </div>

<!-- If user admin show below -->
<?php if ($_SESSION['admin'] == 1) { ?>
    <main style="color: rgb(255, 255, 255); z-index: 0;">
      <section id="top" style="text-align: center; width: 100vw">
        <h1 style="padding: 4px; margin: 0 auto;">Server Dashboard<blink>_</blink></h1>
        <h3 class="rounded" style="margin-bottom: 15px; margin-top: 10px;">
          <span>Welcome </span>
          <span class="rounded" style="text-decoration: underline"><a style="color: yellow" title="Log out" href="dashboard.php?logout='1'"><?php echo $_SESSION['name']; ?></a></span>
        </h3>

        <!-- Cards -->
        <div class="container" style="width: 100%;">
          <div class="row">
            <!-- Command Shell -->
            <div class="col-md-6" style="margin-bottom: 10px;">
              <div class="card" style="font-size: 15px; color: black; height: 100%;">
                <div class="card-header" style="font-weight: bold;">Command Shell</div>
                <div class="card-body">
                  <form method="GET" name="<?php echo basename($_SERVER['PHP_SELF']); ?>">
                    <div class="input-group">
                        <input class="form-control width100" type="TEXT" placeholder="Command" name="cmd" id="cmd">
                        <div class="input-group-append">
                          <button class="btn btn-dark" type="SUBMIT" value="Execute">Enter</button>
                        </div>
                    </div>
                  </form>

                  <pre class="rounded-lg" style="text-align: left; margin-top: 20px; overflow: auto; white-space: pre; word-wrap: normal; color: #eeeeec; background-color: #292929; padding-left: 10px">
                    <code>
                      <?php
                        if(isset($_GET['cmd']))
                        {
                          echo("\n");
                          system($_GET['cmd']);
                        } else {
                          echo("\n");
                          system('df -h');
                        }
                      ?>
                    </code>
                  </pre>
                </div>
              </div>
            </div>
            <!-- End of Command Shell -->

            <!-- Stats -->
            <div class="col-md-6" style="margin-bottom: 10px;">
              <div class="card" style="font-size: 15px; color: black; height: 100%;">
                <div class="card-header" style="font-weight: bold;">Stats</div>
                <div class="card-body">

                  <!-- Storage info -->
                  <p class="card-text">
                    <span style='font-weight: bold;'>Storage:</span><br>
                    <?php
                        $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );

                        // Get disk total and used space
                        $disk_total_space = disk_total_space(".");
                        $disk_free_space = disk_free_space(".");
                        $disk_used_space = $disk_total_space - $disk_free_space;

                        // Calculate percentage
                        $disk_used_percent = ($disk_used_space / $disk_total_space) * 100;
                        $disk_used_percent = round($disk_used_percent, 2);
                        $disk_free_percent = 100 - $disk_used_percent;
                        $disk_free_percent = round($disk_free_percent, 2);

                        // Get size prefix
                        $index = 0;
                        while( $disk_total_space >= 1024 )
                        {
                            $disk_total_space /= 1024;
                            $index++;
                        }

                        // Get free space prefix
                        $index2 = 0;
                        while( $disk_free_space >= 1024 )
                        {
                            $disk_free_space /= 1024;
                            $index2++;
                        }

                        // Calculate prefix for disk_used_space
                        $index3 = 0;
                        while( $disk_used_space >= 1024 )
                        {
                            $disk_used_space /= 1024;
                            $index3++;
                        }
                        
                        // Print info
                        echo "Total: " . round($disk_total_space, 2) . " " . $si_prefix[$index] . "<br>";
                        echo "Used: " . round($disk_used_space, 2) . " " . $si_prefix[$index3] . " (" . $disk_used_percent . "%)<br>";
                        echo "Free: " . round($disk_free_space, 2) . " " . $si_prefix[$index2] . " (" . $disk_free_percent . "%)<br>";

                        // Progress bar of used space
                        $used_space = disk_total_space(".") - disk_free_space(".");
                        $used_space_percentage = round(($used_space / disk_total_space(".")) * 100);
                        echo "<div class='progress' style='height: 20px;'>
                          <div id='storageBar' class='progress-bar' role='progressbar' style='width: $used_space_percentage%;' aria-valuenow='$used_space_percentage' aria-valuemin='0' aria-valuemax='100'>$used_space_percentage% used</div>
                        </div>";

                        dynamicBar($used_space_percentage, "storageBar");
                      ?>
                  </p>
                  <!-- End Storage info -->

                  <hr>

                  <!-- System info -->
                  <p class="card-text">
                    <span style='font-weight: bold;'>System:</span><br>
                    <span>Memory:</span><br>
                    <?php
                      // Get memory usage on windows
                      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                          $base = 1024;
                          $free = shell_exec('wmic OS get FreePhysicalMemory /Value');
                          $free = preg_replace('/\D/', '', $free);
                          $total = shell_exec('wmic OS get TotalVisibleMemorySize /Value');
                          $total = preg_replace('/\D/', '', $total);
                          $used = $total - $free;
                          $percent = $used / $total * 100;
                          $memorypercent = round($percent, 1);
                          
                          $bytes = $total * 1024;
                          $class = min((int)log($bytes , $base) , count($si_prefix) - 1);
                          $total_memory = sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
                          echo "Total: $total_memory<br>";

                          $bytes = $free * 1024;
                          $class = min((int)log($bytes , $base) , count($si_prefix) - 1);
                          $free_memory = sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
                          echo "Free: $free_memory<br>";

                          $bytes = $used * 1024;
                          $class = min((int)log($bytes , $base) , count($si_prefix) - 1);
                          $used_memory = sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
                          echo "Used: $used_memory<br>";

                          echo "<div class='progress' style='height: 20px;'>
                          <div id='memoryBar' class='progress-bar' role='progressbar' style='width: $memorypercent%;' aria-valuenow='$memorypercent' aria-valuemin='0' aria-valuemax='100'>$memorypercent% used</div>
                        </div>";

                        dynamicBar($memorypercent, "memoryBar");
                      } else {
                          // Get memory usage on linux and display like windows
                          $base = 1024;

                          $free = shell_exec('free');
                          $free = (string)trim($free);
                          $free_arr = explode("\n", $free);
                          $mem = explode(" ", $free_arr[1]);
                          $mem = array_filter($mem);
                          $mem = array_merge($mem);
                          $memory_usage = $mem[2] / $mem[1] * 100;
                          $memory_usage = round($memory_usage, 1);

                          $total_bytes = $mem[1] * 1024;
                          $class = min((int)log($total_bytes , $base) , count($si_prefix) - 1);
                          $total_memory = sprintf('%1.2f' , $total_bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
                          echo "Total: $total_memory<br>";

                          $used_bytes = $mem[2] * 1024;
                          $class = min((int)log($used_bytes , $base) , count($si_prefix) - 1);
                          $used_memory = sprintf('%1.2f' , $used_bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
                          // Get used memory %
                          $used_memory_percentage = $used_bytes / $total_bytes * 100;
                          echo "Used: $used_memory (" . round($used_memory_percentage, 1) . "%)<br>";

                          $free_bytes = $mem[3] * 1024;
                          $class = min((int)log($free_bytes , $base) , count($si_prefix) - 1);
                          $free_memory = sprintf('%1.2f' , $free_bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
                          // Get free memory %
                          $free_memory_percentage = $free_bytes / $total_bytes * 100;
                          echo "Free: $free_memory (" . round($free_memory_percentage, 1) . "%)<br>";

                          echo "<div class='progress' style='height: 20px;'>
                          <div id='memoryBar' class='progress-bar' role='progressbar' style='width: $memory_usage%;' aria-valuenow='$memory_usage' aria-valuemin='0' aria-valuemax='100'>$memory_usage% used</div>
                        </div>";

                        dynamicBar($memory_usage, "memoryBar");
                      }

                      // Get current date
                      $date = date('Y-m-d H:i:s');
                    ?>
                    <br><span>CPU:</span><br>
                    <?php
                      // Get CPU usage on windows and linux
                      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                          $cpu = shell_exec('wmic cpu get loadpercentage /Value');
                          $cores = shell_exec('wmic cpu get NumberOfCores /Value');
                          $clock_speed = shell_exec('wmic cpu get MaxClockSpeed /Value');
                          $cpu = preg_replace('/\D/', '', $cpu);
                          $cores = preg_replace('/\D/', '', $cores);
                          $clock_speed = preg_replace('/\D/', '', $clock_speed);
                          // Convert MHz to GHz
                          $clock_speed = round($clock_speed / 1000, 2);

                          echo "$cores cores @ $clock_speed GHz <br>";
                          echo "<div class='progress' style='height: 20px;'>
                          <div id='cpuBar' class='progress-bar' role='progressbar' style='width: $cpu%;' aria-valuenow='$cpu' aria-valuemin='0' aria-valuemax='100'>$cpu% used</div>
                          </div>";

                          dynamicBar($cpu, "cpuBar");
                      } else {
                          // Get cpu usage on linux and display like windows
                          $cpu = shell_exec('top -b -n 1 | grep "Cpu(s)"');
                          $cpu = (string)trim($cpu);
                          $cpu = substr($cpu, strpos($cpu, ':') + 1);
                          $cpu = substr($cpu, 0, strpos($cpu, '%'));
                          $cpu = round($cpu, 1);

                          $cores = shell_exec('nproc');
                          $clock_speed = shell_exec('cat /proc/cpuinfo | grep MHz');
                          $clock_speed = (string)trim($clock_speed);
                          $clock_speed = substr($clock_speed, strpos($clock_speed, ':') + 1);
                          $clock_speed = substr($clock_speed, 0, strpos($clock_speed, 'MHz'));
                          $clock_speed = round($clock_speed, 2);

                          // If nothing returned echo error
                          if ($clock_speed == '') {
                              echo "$cores cores @ CPU does not allow retrieval of clock speed";
                          } elseif ($cores == '') {
                              echo "Could not get cores @ $clock_speed GHz<br>";
                          } else {
                              echo "$cores cores @ $clock_speed GHz <br>";
                              echo "<div class='progress' style='height: 20px;'>
                              <div id='cpuBar' class='progress-bar' role='progressbar' style='width: $cpu%;' aria-valuenow='$cpu' aria-valuemin='0' aria-valuemax='100'>$cpu% used</div>
                              </div>";

                              dynamicBar($cpu, "cpuBar");
                          }
                      }
                    ?>
                  </p>
                  <!-- End System info -->
                  
                  <hr>

                  <!-- Network info -->
                  <p class="card-text">
                    <span style='font-weight: bold;'>Network:</span><br>
                    <?php
                      // Get public server IP
                      $public_ip = file_get_contents('https://api.ipify.org');
                      echo "Public IP: $public_ip <br>";
                      
                      // Get local server IP on windows and linux
                      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        // Get preferred local IPv4 address
                        $local_ip = getHostByName(getHostName());
                      } else {
                          $local_ip = shell_exec('hostname -I');
                          $local_ip = (string)trim($local_ip);
                          $local_ip = substr($local_ip, 0, strpos($local_ip, ' '));
                      }
                      echo "Local IP: $local_ip <br>";

                      // Get local server uptime on windows and linux
                      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                          $uptime = shell_exec('wmic OS get LastBootUpTime /Value');
                          $uptime = (string)trim($uptime);
                          $uptime = substr($uptime, strpos($uptime, '=') + 1);
                          $uptime = str_replace('T', ' ', $uptime);
                          $uptime = str_replace('+', ' ', $uptime);
                          $uptime = str_replace('.', ' ', $uptime);
                          $uptime = substr($uptime, 0, strpos($uptime, ' '));
                          $uptime = strtotime($uptime);
                          $uptime = time() - $uptime;
                          $uptime = round($uptime / 3600, 1);
                      } else {
                          $uptime = shell_exec('cat /proc/uptime');
                          $uptime = (string)trim($uptime);
                          $uptime = substr($uptime, 0, strpos($uptime, '.'));
                          $uptime = round($uptime / 3600, 1);
                      }

                      // Get hostname
                      $host = exec('hostname');
                      echo "Hostname: $host<br>";

                      // Get OS
                      $os = php_uname();
                      echo "OS: $os<br>";

                    ?>
                  </p>
                </div>
                <div class="card-footer">
                  <small class="text-muted">Last updated: <?php echo $date; ?> | Uptime: <?php echo $uptime; ?> hours</small>
                </div>
                </div>
              </div>
            </div>
            <!-- End of Stats -->

          </div>
        </div>
        <!-- End of Cards -->

      </section>
      <section id="bottom" style="text-align: center; width: 100vw; height: auto">
        <div class="container" style="margin: 0 auto;">
          <div class="row">
            <!-- Big centered table with logs -->
            <div class="col" style="margin-top: 15px;">
              <div class="card" style="font-size: 15px; color: black;">
                <div class="card-header" style="font-weight: bold;">Logs - <a style="text-decoration: underline; color: black" href="dashboard.php?logs=clear">clear</a></div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table" style="font-size: 15px">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Email</th>
                          <th scope="col">IP</th>
                          <th scope="col">Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          // Get all logs from database and sort by row ID
                          $sql = "SELECT * FROM login ORDER BY id DESC LIMIT 50";
                          $result = mysqli_query($db, $sql);
                          
                          // If no results display message
                          if (mysqli_num_rows($result) == 0) {
                            echo "<tr><td colspan='4'>No logs found</td></tr>";
                          } else {
                            // Display logs
                            $i = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                              echo "<tr>";
                              echo "<th scope='row'>" . $i . "</th>";
                              echo "<td>".$row['email']."</td>";
                              echo "<td>".$row['ip']."</td>";
                              echo "<td>".$row['date']."</td>";
                              echo "</tr>";
                              $i++;
                            }
                          }
                          
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- End of Big centered table with logs -->
          </div>
        </div>
        <div class="container" style="margin: 0 auto; margin-top: 15px">
          <div class="row">

            <!-- Notes Section -->
            <div class="col" style="margin-top: 15px; margin-bottom: 30px">
              <div class="card" style="font-size: 15px; color: black;">
                <div class="card-header" style="font-weight: bold;">Notes - <a style="text-decoration: underline; color: black" href="dashboard.php?notes=clear">clear</a></div>
                <div class="card-body" style="font-size: 15px;">
                  <form class="d-flex" style="margin: 5px;" action="dashboard.php" method="post">
                      <input class="form-control" type="text" name="title" required="" placeholder="Post title" style="width: 50%;">
                      <input class="form-control" type="text" name="text" required="" placeholder="Post text" style="margin-left: 5px;width: 80%;">
                      <button class="btn btn-dark" name="post_note" type="submit" style="margin-left: 5px;">Submit</button>
                  </form>

                  <div id="coolGrid">        
                      <?php

                      # get text from database
                      $username = $_SESSION['name'];
                      $sql = "SELECT * FROM cards WHERE username='$username' ORDER BY id DESC";
                      $result = $db->query($sql);

                      # display text as cards
                      if ($result->num_rows >= 3) {
                          // output data of each row
                          // if on mobile display cards in 1x1 grid
                          if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false) {
                            echo "<style> #coolGrid {display: grid; grid-template-columns: repeat(1, 1fr); gap: 5px;} </style>";
                          } else {
                            echo "<style> #coolGrid {display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px;} </style>";
                          }
                          while ($row = $result->fetch_assoc()) {
                              echo '<div class="card">';
                              echo '<div class="card-body">';
                              echo '<h5 class="card-title">' . $row["title"] . ' - <a style="text-decoration: underline; color: black" href="dashboard.php?del_note= ' . $row["id"] . '">Delete</a></h5>';
                              echo '<p class="card-text">' . $row["text"] . '</p><br>';
                              echo '<p class="card-text">' . $row["date"] . '</p>';
                              echo '</div>';
                              echo '</div>';
                          }
                      } elseif ($result->num_rows == 2) {
                          // output data of each row
                          // if on mobile display cards in 1x1 grid
                          if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false) {
                            echo "<style> #coolGrid {display: grid; grid-template-columns: repeat(1, 1fr); gap: 5px;} </style>";
                          } else {
                            echo "<style> #coolGrid {display: grid; grid-template-columns: repeat(2, 1fr); gap: 5px;} </style>";
                          }
                          while ($row = $result->fetch_assoc()) {
                              echo '<div class="card">';
                              echo '<div class="card-body">';
                              echo '<h5 class="card-title">' . $row["title"] . ' - <a style="text-decoration: underline; color: black" href="dashboard.php?del_note= ' . $row["id"] . '">Delete</a></h5>';
                              echo '<p class="card-text">' . $row["text"] . '</p><br>';
                              echo '<p class="card-text">' . $row["date"] . '</p>';
                              echo '</div>';
                              echo '</div>';
                          }
                      } elseif ($result->num_rows == 1) {
                          // output data of each row
                          echo "<style> #coolGrid {display: grid; grid-template-columns: repeat(1, 1fr); gap: 5px;} </style>";
                          while ($row = $result->fetch_assoc()) {
                              echo '<div class="card">';
                              echo '<div class="card-body">';
                              echo '<h5 class="card-title">' . $row["title"] . ' - <a style="text-decoration: underline; color: black" href="dashboard.php?del_note= ' . $row["id"] . '">Delete</a></h5>';
                              echo '<p class="card-text">' . $row["text"] . '</p><br>';
                              echo '<p class="card-text">' . $row["date"] . '</p>';
                              echo '</div>';
                              echo '</div>';
                          }
                      } else {
                          echo "<style> #coolGrid {display: grid; grid-template-columns: repeat(1, 1fr); gap: 5px;} </style>";
                          echo '<div class="card">';
                          echo '<div class="card-body">';
                          echo '<h5 class="card-title">Hey...</h5>';
                          echo '<p class="card-text">Maybe submit something, its pretty empty here</p>';
                          echo '</div>';
                          echo '</div>';
                      }
                      ?>
                  </div>
                </div>
              </div>
            </div>
            <!-- End of Notes Section -->

          </div>
        </div>
      </section>
    </main>

<!-- If user not admin show below -->
<?php } else { ?>
  <main style="color: rgb(255, 255, 255); z-index: 0;">
      <section id="top" style="text-align: center; width: 100vw">
        <h1 style="padding: 4px; margin: 0 auto;">Server Dashboard<blink>_</blink></h1>
        <h3 class="rounded" style="margin-bottom: 15px; margin-top: 10px;">
          <span>Welcome </span>
          <span class="rounded" style="text-decoration: underline"><a style="color: inherit" title="Log out" href="dashboard.php?logout='1'"><?php echo $_SESSION['name']; ?></a></span>
        </h3>

        <!-- Cards -->
        <div class="container">
          <div class="row">

            <!-- Stats -->
            <div class="col" style="margin-bottom: 10px;">
              <div class="card" style="font-size: 15px; color: black; height: 100%;">
                <div class="card-header" style="font-weight: bold;">Stats</div>
                <div class="card-body">

                  <!-- Storage info -->
                  <p class="card-text">
                    <span style='font-weight: bold;'>Storage:</span><br>
                    <?php
                        $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );

                        // Get disk total and used space
                        $disk_total_space = disk_total_space(".");
                        $disk_free_space = disk_free_space(".");
                        $disk_used_space = $disk_total_space - $disk_free_space;

                        // Calculate percentage
                        $disk_used_percent = ($disk_used_space / $disk_total_space) * 100;
                        $disk_used_percent = round($disk_used_percent, 2);
                        $disk_free_percent = 100 - $disk_used_percent;
                        $disk_free_percent = round($disk_free_percent, 2);

                        // Get size prefix
                        $index = 0;
                        while( $disk_total_space >= 1024 )
                        {
                            $disk_total_space /= 1024;
                            $index++;
                        }

                        // Get free space prefix
                        $index2 = 0;
                        while( $disk_free_space >= 1024 )
                        {
                            $disk_free_space /= 1024;
                            $index2++;
                        }

                        // Calculate prefix for disk_used_space
                        $index3 = 0;
                        while( $disk_used_space >= 1024 )
                        {
                            $disk_used_space /= 1024;
                            $index3++;
                        }
                        
                        // Print info
                        echo "Total: " . round($disk_total_space, 2) . " " . $si_prefix[$index] . "<br>";
                        echo "Used: " . round($disk_used_space, 2) . " " . $si_prefix[$index3] . " (" . $disk_used_percent . "%)<br>";
                        echo "Free: " . round($disk_free_space, 2) . " " . $si_prefix[$index2] . " (" . $disk_free_percent . "%)<br>";

                        // Progress bar of used space
                        $used_space = disk_total_space(".") - disk_free_space(".");
                        $used_space_percentage = round(($used_space / disk_total_space(".")) * 100);
                        echo "<div class='progress' style='height: 20px;'>
                          <div id='storageBar' class='progress-bar' role='progressbar' style='width: $used_space_percentage%;' aria-valuenow='$used_space_percentage' aria-valuemin='0' aria-valuemax='100'>$used_space_percentage% used</div>
                        </div>";

                        dynamicBar($used_space_percentage, "storageBar");
                      ?>
                  </p>
                  <!-- End Storage info -->

                  <hr>

                  <!-- System info -->
                  <p class="card-text">
                    <span style='font-weight: bold;'>System:</span><br>
                    <span>Memory:</span><br>
                    <?php
                      // Get memory usage on windows
                      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                          $base = 1024;
                          $free = shell_exec('wmic OS get FreePhysicalMemory /Value');
                          $free = preg_replace('/\D/', '', $free);
                          $total = shell_exec('wmic OS get TotalVisibleMemorySize /Value');
                          $total = preg_replace('/\D/', '', $total);
                          $used = $total - $free;
                          $percent = $used / $total * 100;
                          $memorypercent = round($percent, 1);
                          
                          $bytes = $total * 1024;
                          $class = min((int)log($bytes , $base) , count($si_prefix) - 1);
                          $total_memory = sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
                          echo "Total: $total_memory<br>";

                          $bytes = $free * 1024;
                          $class = min((int)log($bytes , $base) , count($si_prefix) - 1);
                          $free_memory = sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
                          echo "Free: $free_memory<br>";

                          $bytes = $used * 1024;
                          $class = min((int)log($bytes , $base) , count($si_prefix) - 1);
                          $used_memory = sprintf('%1.2f' , $bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
                          echo "Used: $used_memory<br>";

                          echo "<div class='progress' style='height: 20px;'>
                          <div id='memoryBar' class='progress-bar' role='progressbar' style='width: $memorypercent%;' aria-valuenow='$memorypercent' aria-valuemin='0' aria-valuemax='100'>$memorypercent% used</div>
                        </div>";

                        dynamicBar($memorypercent, "memoryBar");
                      } else {
                          // Get memory usage on linux and display like windows
                          $base = 1024;

                          $free = shell_exec('free');
                          $free = (string)trim($free);
                          $free_arr = explode("\n", $free);
                          $mem = explode(" ", $free_arr[1]);
                          $mem = array_filter($mem);
                          $mem = array_merge($mem);
                          $memory_usage = $mem[2] / $mem[1] * 100;
                          $memory_usage = round($memory_usage, 1);

                          $total_bytes = $mem[1] * 1024;
                          $class = min((int)log($total_bytes , $base) , count($si_prefix) - 1);
                          $total_memory = sprintf('%1.2f' , $total_bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
                          echo "Total: $total_memory<br>";

                          $used_bytes = $mem[2] * 1024;
                          $class = min((int)log($used_bytes , $base) , count($si_prefix) - 1);
                          $used_memory = sprintf('%1.2f' , $used_bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
                          // Get used memory %
                          $used_memory_percentage = $used_bytes / $total_bytes * 100;
                          echo "Used: $used_memory (" . round($used_memory_percentage, 1) . "%)<br>";

                          $free_bytes = $mem[3] * 1024;
                          $class = min((int)log($free_bytes , $base) , count($si_prefix) - 1);
                          $free_memory = sprintf('%1.2f' , $free_bytes / pow($base,$class)) . ' ' . $si_prefix[$class];
                          // Get free memory %
                          $free_memory_percentage = $free_bytes / $total_bytes * 100;
                          echo "Free: $free_memory (" . round($free_memory_percentage, 1) . "%)<br>";

                          echo "<div class='progress' style='height: 20px;'>
                          <div id='memoryBar' class='progress-bar' role='progressbar' style='width: $memory_usage%;' aria-valuenow='$memory_usage' aria-valuemin='0' aria-valuemax='100'>$memory_usage% used</div>
                        </div>";

                        dynamicBar($memory_usage, "memoryBar");
                      }

                      // Get current date
                      $date = date('Y-m-d H:i:s');
                    ?>
                    <br><span>CPU:</span><br>
                    <?php
                      // Get CPU usage on windows and linux
                      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                          $cpu = shell_exec('wmic cpu get loadpercentage /Value');
                          $cores = shell_exec('wmic cpu get NumberOfCores /Value');
                          $clock_speed = shell_exec('wmic cpu get MaxClockSpeed /Value');
                          $cpu = preg_replace('/\D/', '', $cpu);
                          $cores = preg_replace('/\D/', '', $cores);
                          $clock_speed = preg_replace('/\D/', '', $clock_speed);
                          // Convert MHz to GHz
                          $clock_speed = round($clock_speed / 1000, 2);

                          echo "$cores cores @ $clock_speed GHz <br>";
                          echo "<div class='progress' style='height: 20px;'>
                          <div id='cpuBar' class='progress-bar' role='progressbar' style='width: $cpu%;' aria-valuenow='$cpu' aria-valuemin='0' aria-valuemax='100'>$cpu% used</div>
                          </div>";

                          dynamicBar($cpu, "cpuBar");
                      } else {
                          // Get cpu usage on linux and display like windows
                          $cpu = shell_exec('top -b -n 1 | grep "Cpu(s)"');
                          $cpu = (string)trim($cpu);
                          $cpu = substr($cpu, strpos($cpu, ':') + 1);
                          $cpu = substr($cpu, 0, strpos($cpu, '%'));
                          $cpu = round($cpu, 1);

                          $cores = shell_exec('nproc');
                          $clock_speed = shell_exec('cat /proc/cpuinfo | grep MHz');
                          $clock_speed = (string)trim($clock_speed);
                          $clock_speed = substr($clock_speed, strpos($clock_speed, ':') + 1);
                          $clock_speed = substr($clock_speed, 0, strpos($clock_speed, 'MHz'));
                          $clock_speed = round($clock_speed, 2);

                          // If nothing returned echo error
                          if ($clock_speed == '') {
                              echo "$cores cores @ CPU does not allow retrieval of clock speed";
                          } elseif ($cores == '') {
                              echo "Could not get cores @ $clock_speed GHz<br>";
                          } else {
                              echo "$cores cores @ $clock_speed GHz <br>";
                              echo "<div class='progress' style='height: 20px;'>
                              <div id='cpuBar' class='progress-bar' role='progressbar' style='width: $cpu%;' aria-valuenow='$cpu' aria-valuemin='0' aria-valuemax='100'>$cpu% used</div>
                              </div>";

                              dynamicBar($cpu, "cpuBar");
                          }
                      }
                    ?>
                  </p>
                  <!-- End System info -->
                  
                  <hr>

                  <!-- Network info -->
                  <p class="card-text">
                    <span style='font-weight: bold;'>Network:</span><br>
                    <?php
                      // Get local server uptime on windows and linux
                      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                          $uptime = shell_exec('wmic OS get LastBootUpTime /Value');
                          $uptime = (string)trim($uptime);
                          $uptime = substr($uptime, strpos($uptime, '=') + 1);
                          $uptime = str_replace('T', ' ', $uptime);
                          $uptime = str_replace('+', ' ', $uptime);
                          $uptime = str_replace('.', ' ', $uptime);
                          $uptime = substr($uptime, 0, strpos($uptime, ' '));
                          $uptime = strtotime($uptime);
                          $uptime = time() - $uptime;
                          $uptime = round($uptime / 3600, 1);
                      } else {
                          $uptime = shell_exec('cat /proc/uptime');
                          $uptime = (string)trim($uptime);
                          $uptime = substr($uptime, 0, strpos($uptime, '.'));
                          $uptime = round($uptime / 3600, 1);
                      }

                      // Get hostname
                      $host = exec('hostname');
                      echo "Hostname: $host<br>";

                      // Get OS
                      $os = php_uname();
                      echo "OS: $os<br>";

                    ?>
                  </p>
                </div>
                <div class="card-footer">
                  <small class="text-muted">Last updated: <?php echo $date; ?> | Uptime: <?php echo $uptime; ?> hours</small>
                </div>
                </div>
              </div>
            </div>
            <!-- End of Stats -->

          </div>
        </div>
        <!-- End of Cards -->

      </section>
      <section id="bottom" style="text-align: center; width: 100vw; height: auto">
        <div class="container" style="margin: 0 auto; margin-top: 15px">
          <div class="row">

            <!-- Notes Section -->
            <div class="col" style="margin-top: 15px; margin-bottom: 30px">
              <div class="card" style="font-size: 15px; color: black;">
                <div class="card-header" style="font-weight: bold;">Notes - <a style="text-decoration: underline; color: black" href="dashboard.php?notes=clear">clear</a></div>
                <div class="card-body" style="font-size: 15px;">
                  <form class="d-flex" style="margin: 5px;" action="dashboard.php" method="post">
                      <input class="form-control" type="text" name="title" required="" placeholder="Post title" style="width: 50%;">
                      <input class="form-control" type="text" name="text" required="" placeholder="Post text" style="margin-left: 5px;width: 80%;">
                      <button class="btn btn-dark" name="post_note" type="submit" style="margin-left: 5px;">Submit</button>
                  </form>

                  <div id="coolGrid">        
                      <?php

                      # get text from database
                      $username = $_SESSION['name'];
                      $sql = "SELECT * FROM cards WHERE username='$username' ORDER BY id DESC";
                      $result = $db->query($sql);

                      # display text as cards
                      if ($result->num_rows >= 3) {
                          // output data of each row
                          // if on mobile display cards in 1x1 grid
                          if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false) {
                            echo "<style> #coolGrid {display: grid; grid-template-columns: repeat(1, 1fr); gap: 5px;} </style>";
                          } else {
                            echo "<style> #coolGrid {display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px;} </style>";
                          }
                          while ($row = $result->fetch_assoc()) {
                              echo '<div class="card">';
                              echo '<div class="card-body">';
                              echo '<h5 class="card-title">' . $row["title"] . ' - <a style="text-decoration: underline; color: black" href="dashboard.php?del_note= ' . $row["id"] . '">Delete</a></h5>';
                              echo '<p class="card-text">' . $row["text"] . '</p><br>';
                              echo '<p class="card-text">' . $row["date"] . '</p>';
                              echo '</div>';
                              echo '</div>';
                          }
                      } elseif ($result->num_rows == 2) {
                          // output data of each row
                          // if on mobile display cards in 1x1 grid
                          if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false) {
                            echo "<style> #coolGrid {display: grid; grid-template-columns: repeat(1, 1fr); gap: 5px;} </style>";
                          } else {
                            echo "<style> #coolGrid {display: grid; grid-template-columns: repeat(2, 1fr); gap: 5px;} </style>";
                          }
                          while ($row = $result->fetch_assoc()) {
                              echo '<div class="card">';
                              echo '<div class="card-body">';
                              echo '<h5 class="card-title">' . $row["title"] . ' - <a style="text-decoration: underline; color: black" href="dashboard.php?del_note= ' . $row["id"] . '">Delete</a></h5>';
                              echo '<p class="card-text">' . $row["text"] . '</p><br>';
                              echo '<p class="card-text">' . $row["date"] . '</p>';
                              echo '</div>';
                              echo '</div>';
                          }
                      } elseif ($result->num_rows == 1) {
                          // output data of each row
                          echo "<style> #coolGrid {display: grid; grid-template-columns: repeat(1, 1fr); gap: 5px;} </style>";
                          while ($row = $result->fetch_assoc()) {
                              echo '<div class="card">';
                              echo '<div class="card-body">';
                              echo '<h5 class="card-title">' . $row["title"] . ' - <a style="text-decoration: underline; color: black" href="dashboard.php?del_note= ' . $row["id"] . '">Delete</a></h5>';
                              echo '<p class="card-text">' . $row["text"] . '</p><br>';
                              echo '<p class="card-text">' . $row["date"] . '</p>';
                              echo '</div>';
                              echo '</div>';
                          }
                      } else {
                          echo "<style> #coolGrid {display: grid; grid-template-columns: repeat(1, 1fr); gap: 5px;} </style>";
                          echo '<div class="card">';
                          echo '<div class="card-body">';
                          echo '<h5 class="card-title">Hey...</h5>';
                          echo '<p class="card-text">Maybe submit something, its pretty empty here</p>';
                          echo '</div>';
                          echo '</div>';
                      }
                      ?>
                  </div>
                </div>
              </div>
            </div>
            <!-- End of Notes Section -->

          </div>
        </div>
      </section>
    </main>
<?php } ?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>