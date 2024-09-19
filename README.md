
# GitHub Unfollowers Tracker

This PHP script helps you identify who isn't following you back on GitHub. The script compares your followers with the users you follow and lists those who aren't following you back.

## Features

- **Identify Unfollowers:** See which users you're following on GitHub that aren't following you back.
- **No Authentication Required:** The script works without requiring a personal access token.
- **Customizable Allowed Accounts:** You can exclude certain accounts from being flagged as unfollowers by using a custom list.

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/binahayat00/git.git
   ```
2. **Navigate to the project directory:**
   ```bash
   cd git
   ```
3. **Create the `listOfAllowedAccounts.php` file:**
   - Copy the sample file:
     ```bash
     cp listOfAllowedAccounts-sample.php listOfAllowedAccounts.php
     ```
   - Open `listOfAllowedAccounts.php` and add the GitHub usernames that you want to exclude from the unfollowers list.
   - For example, if you follow `github.com/sample` and don't want it to be flagged as an unfollower, add it to the array in this file:
     ```php
     <?php
     return [
         'sample',
         // Add other usernames here
     ];
     ```
   > Note: `listOfAllowedAccounts.php` is included in the `.gitignore` file, so it won't be tracked by Git.

## Usage

To run the script and see who isn't following you back, use the following command:

```bash
php whoDoNotFollowYouInGit.php
```

The script will output a list of users you're following but who aren't following you back, excluding those listed in `listOfAllowedAccounts.php`.

## Example Output

```plaintext
These users are not following you back:
- username1
- username2
- username3
```

## Contributing

If you'd like to contribute to this project, feel free to fork the repository, make your changes, and submit a pull request. Contributions are welcome!

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
