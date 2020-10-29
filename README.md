# MailerLite-Test
Welcome to my MailerLite test!

API:
  The JSON API is located in the api.mailerlite.localhost folder. To launch it just open the CLI, cd into it and run `composer install`. 
  The DB is located in the root folder and the credentials are in /Core/config.php
  
App/Frontend:
  The App is already compiled in app.mailerlite.localhost/dist folder, the API calls are hardcoded to 'http://api.mailerlite.localhost'.
  If you wish to change something, just run `npn install` first
  
The package used to validate the email addresses is using 'info@mailerlite.com' as default to the be provided to the API.
