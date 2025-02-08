![image](https://github.com/user-attachments/assets/eee8ade2-29be-406d-9388-f8c1d09383f2)

# Backnadya Cookie Setter

This PHP script provides a mechanism to set a cookie (`current_cache`) based on user input and then use this value to load remote PHP files. It's useful for testing and interacting with remote content securely via HTTPS.

### Features:
- **Interactive Mode**: Allows the user to set the `current_cache` cookie to different values (URLs) by pressing keys (1, 2, or 3).
- **Remote Content Fetching**: Retrieves remote PHP content via `cURL`, `file_get_contents()`, or `fopen()`.
- **Secure HTTPS Handling**: Ensures that only HTTPS URLs are used for fetching remote content.
- **Temporary File Handling**: Safely stores and includes remote PHP files after validating their content.

---

## Usage

1. **Setting the Cookie**:  
   Press one of the keys `1`, `2`, or `3` to set the `current_cache` cookie to different URL values. The available URLs can be modified in the script.

   - **Key `1`** sets the cookie to: `https://yourserver/yournadya.php`
   - **Key `2`** sets the cookie to: `https://yourserver/yournadya1.php`
   - **Key `3`** sets the cookie to: `https://yourserver/yournadya2.php`

2. **Accessing Remote Content**:  
   After setting the cookie, visit the URL `https://yourserver/yournadya.php` to run the remote file based on the cookie value.

3. **Interactive Mode**:  
   To remain in interactive mode, ensure that the URL contains the parameter `?backnadya=1`. This will allow you to set the cookie again without exiting the page.

---

## Security and Legal Disclaimers

### Unauthorized Access is Prohibited
This script is not intended for unauthorized access to any system or resource. Use it only for legitimate purposes and with proper authorization. Any attempt to access or modify data without permission is a violation of laws and regulations.

### Use Responsibly
This script allows fetching and executing remote PHP content based on a set cookie. Ensure that the content being executed is safe and authorized. Always use this script in a legal and ethical manner.

### Disclaimer of Liability
We do not take responsibility for any illegal, unauthorized, or unethical use of this script. By using this script, you agree to comply with all applicable laws, regulations, and terms of service. Any actions that violate these rules are the sole responsibility of the user.

---

## Installation

1. Clone this repository or download the script.
2. Upload the PHP file to your web server.
3. Access the script through your browser and follow the instructions to interact with the cookie-setting functionality.

---

## Contributing

If you'd like to contribute to the project, feel free to submit a pull request or open an issue.
